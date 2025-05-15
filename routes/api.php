<?php

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
use App\Http\Controllers\Api\V1\Project\ReplicateProject;
use App\Http\Controllers\Api\V1\Project\RevisionController;
use App\Http\Controllers\Api\V1\ResourceItem\ResourceItemController;
use App\Http\Controllers\Api\V1\Task\TaskController;
use App\Http\Controllers\APiSyncController;
use App\Http\Resources\User\UserCollection;
use App\Models\ResourceName;
use App\Models\Uom;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function () {
        return response()->json(new UserCollection(Auth::user()), 200);
    });

    Route::get('/project-status', function () {
        return response()->json(ProjectStatus::cases(), 200);
    });

    Route::get('/project-stage', function () {
        return response()->json(ProjectStage::cases(), 200);
    });

    Route::get('/resource-names', function () {
        return response()->json(ResourceName::all(), 200);
    });

    // APPROVALS ROUTES
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
    });

    Route::resource('/projects', ProjectController::class);
    // project status updates
    Route::post('/projects/{project}/archive', [ProjectStatusController::class, 'archive']);
    Route::post('/projects/{project}/complete', [ProjectStatusController::class, 'complete']);
    Route::patch('/projects/{project}/status', [ProjectStatusController::class, 'updateStatus']);
    // duplicate/clone project
    Route::post('/projects/replicate', [ProjectController::class, 'replicate']);

    Route::post('/projects/{project}/attachments', [ProjectAttachmentController::class, 'store']);
    Route::delete('/attachments/{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);

    Route::resource('/phases', PhaseController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/resource-items', ResourceItemController::class);

    Route::prefix('project-revisions')->group(function () {
        Route::post('change-to-proposal', [RevisionController::class, 'changeToProposal']);
        Route::post('return-to-draft', [RevisionController::class, 'returnToDraft']);
    });
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);

    Route::resource('/logs', LogController::class);

    Route::get('/employees', GetAllEmployees::class);
    Route::get('/employee/{employee}', ShowEmployee::class);

    Route::get('/project-assignments/{project}/team', [ProjectAssignmentController::class, 'index']);
    Route::get('/project-assignments/{project_assignment}', [ProjectAssignmentController::class, 'show']);
    Route::post('/project-assignments', [ProjectAssignmentController::class, 'store']);

    Route::resource('/positions', PositionController::class);

    Route::get('/uom', function () {
        return response()->json(Uom::all(), 200);
    });
    Route::post('/projects/change-summary-rates', [ProjectController::class, 'changeSummaryRates']);
});
