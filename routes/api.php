<?php

use App\Enums\NatureOfWork;
use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Actions\Approvals\ApproveApproval;
use App\Http\Controllers\Actions\Approvals\DisapproveApproval;
use App\Http\Controllers\Api\V1\Accessibility\PermissionController;
use App\Http\Controllers\Api\V1\Accessibility\RoleController;
use App\Http\Controllers\Api\V1\Assignment\ProjectAssignmentController;
use App\Http\Controllers\Api\V1\Employee\GetAllEmployees;
use App\Http\Controllers\Api\V1\Employee\ShowEmployee;
use App\Http\Controllers\Api\V1\Logs\LogController;
use App\Http\Controllers\Api\V1\Phase\PhaseController;
use App\Http\Controllers\Api\V1\Position\PositionController;
use App\Http\Controllers\Api\V1\Project\ProjectAttachmentController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\Project\ProjectStatusController;
use App\Http\Controllers\Api\V1\Project\RevisionController;
use App\Http\Controllers\Api\V1\ResourceItem\ResourceItemController;
use App\Http\Controllers\Api\V1\Task\TaskController;
use App\Http\Controllers\APiSyncController;
use App\Http\Controllers\ApiServiceController;
use App\Http\Resources\User\UserCollection;
use App\Models\ResourceName;
use App\Models\Uom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// SYNCHRONIZATION ROUTES
Route::prefix('sync')->group(function () {
    Route::prefix('inventory')->group(function () {
        Route::post('/uom', [APiSyncController::class, 'syncUom']);
    });
});
Route::get('nature-of-works', function () {
    return response()->json(NatureOfWork::cases(), 200);
});

Route::middleware('auth:api')->group(function () {

    // ────── User Info ──────
    Route::get('/user', fn() => response()->json(new UserCollection(Auth::user()), 200));

    // ────── Lookups ──────
    Route::prefix('lookups')->group(function () {
        Route::get('/project-status', fn() => response()->json(ProjectStatus::cases(), 200));
        Route::get('/project-stage', fn() => response()->json(ProjectStage::cases(), 200));
        Route::get('/resource-names', fn() => response()->json(ResourceName::all(), 200));
        Route::get('/uom', fn() => response()->json(Uom::all(), 200));
        Route::get('/positions', [PositionController::class, 'index']);
        Route::get('/all-position', [PositionController::class, 'all']);
    });

    // ────── Approvals ──────
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
    });

    // ────── Projects ──────
    Route::prefix('projects')->group(function () {
        Route::apiResource('/', ProjectController::class)->parameters(['' => 'project']);
        Route::patch('{project}/status', [ProjectStatusController::class, 'updateStatus']);
        Route::patch('{id}/update-stage', [ProjectController::class, 'updateStage']);
        Route::post('{project}/archive', [ProjectStatusController::class, 'archive']);
        Route::post('{project}/complete', [ProjectStatusController::class, 'complete']);
        Route::post('replicate', [ProjectController::class, 'replicate']);
        Route::post('{project}/attachments', [ProjectAttachmentController::class, 'store']);
        Route::get('{project}/document-viewer', [ProjectAttachmentController::class, 'generateUrl']);
        Route::post('change-summary-rates', [ProjectController::class, 'changeSummaryRates']);
    });

    // ────── Attachments ──────
    Route::prefix('attachments')->group(function () {
        Route::post('upload', [ProjectAttachmentController::class, 'uploadAttachment']);
        Route::delete('{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);
    });

    // ────── Phases, Tasks, Resources ──────
    Route::apiResource('phases', PhaseController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('resource-items', ResourceItemController::class);

    // ────── Revisions ──────
    Route::prefix('project-revisions')->group(function () {
        Route::post('change-to-proposal', [RevisionController::class, 'changeToProposal']);
        Route::post('return-to-draft', [RevisionController::class, 'returnToDraft']);
    });

    // ────── Roles & Permissions ──────
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);

    // ────── Logs ──────
    Route::apiResource('logs', LogController::class);

    // ────── Employees ──────
    Route::prefix('employees')->group(function () {
        Route::get('/', GetAllEmployees::class);
        Route::get('{employee}', ShowEmployee::class);
    });

    // ────── Project Assignments ──────
    Route::prefix('project-assignments')->group(function () {
        Route::get('{project}/team', [ProjectAssignmentController::class, 'index']);
        Route::get('{project_assignment}', [ProjectAssignmentController::class, 'show']);
        Route::post('/', [ProjectAssignmentController::class, 'store']);
    });
});

// SECRET API KEY ROUTES
Route::middleware("secret_api")->group(function () {
    // SIGMA SERVICES ROUTES
    Route::prefix('sigma')->group(function () {
        Route::prefix('sync-list')->group(function () {
            Route::get("projects", [ApiServiceController::class, "getProjectList"]);
        });
    });
});
