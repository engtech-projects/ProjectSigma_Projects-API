<?php
use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Actions\Approvals\ApproveApproval;
use App\Http\Controllers\Actions\Approvals\DisapproveApproval;
use App\Http\Controllers\Api\V1\Accessibility\PermissionController;
use App\Http\Controllers\Api\V1\Accessibility\RoleController;
use App\Http\Controllers\Api\V1\Assignment\ProjectAssignmentController;
use App\Http\Controllers\Api\V1\Logs\LogController;
use App\Http\Controllers\Api\V1\BoqPart\BoqPartController;
use App\Http\Controllers\Api\V1\Position\PositionController;
use App\Http\Controllers\Api\V1\Project\ProjectAttachmentController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\Project\ProjectStatusController;
use App\Http\Controllers\Api\V1\Project\RevisionController;
use App\Http\Controllers\Api\V1\ResourceItem\ResourceItemController;
use App\Http\Controllers\Api\V1\BoqItem\BoqItemController;
use App\Http\Controllers\Api\V1\Uom\UomController;
use App\Http\Controllers\APiSyncController;
use App\Http\Controllers\ApiServiceController;
use App\Http\Controllers\DirectCostEstimateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NatureOfWorkController;
use App\Http\Controllers\ResourceMetricController;
use App\Http\Controllers\SetupDocumentSignatureController;
use App\Http\Controllers\SetupListsController;
use App\Http\Controllers\TaskScheduleController;
use App\Http\Resources\User\UserCollection;
use App\Models\Uom;
use Illuminate\Support\Facades\Artisan;
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
Route::middleware('auth:api')->group(function () {
    // SYNCHRONIZATION ROUTES
    Route::prefix('setup')->group(function () {
        Route::prefix('sync')->group(function () {
            Route::post('/all', [ApiSyncController::class, 'syncAll']);
            Route::prefix('inventory')->group(function () {
                Route::post('/all', [ApiSyncController::class, 'syncAllInventory']);
                Route::post('/uom', [APiSyncController::class, 'syncUom']);
                Route::post('/item-profile', [APiSyncController::class, 'syncItemProfile']);
            });
            Route::prefix('hrms')->group(function () {
                Route::post('/all', [ApiSyncController::class, 'syncAllHrms']);
                Route::post('/employees', [APiSyncController::class, 'syncEmployees']);
                Route::post('/accessibilities', [APiSyncController::class, 'syncAccessibilities']);
                Route::post('/departments', [APiSyncController::class, 'syncDepartments']);
            });
        });
        Route::prefix('lists')->group(function () {
            Route::get('/uom', [SetupListsController::class, 'getUomList']);
            Route::get('/item-profile', [SetupListsController::class, 'getItemProfileList']);
            Route::get('/employees', [SetupListsController::class, 'getItemProfileList']);
            Route::get('/accessibilities', [SetupListsController::class, 'getAccessibilityList']);
            Route::get('/departments', [SetupListsController::class, 'getDepartmentList']);
        });
    });
    // ────── User Info ──────
    Route::get('/user', fn () => response()->json(new UserCollection(Auth::user()), 200));
    // ────── Lookups ──────
    Route::prefix('lookups')->group(function () {
        Route::get('/project-status', fn () => response()->json(ProjectStatus::cases(), 200));
        Route::get('/project-stage', fn () => response()->json(ProjectStage::cases(), 200));
        Route::get('/resource-names', [ResourceItemController::class, 'getResourceType']);
        Route::get('/uom', fn () => response()->json(Uom::all(), 200));
        Route::resource('positions', PositionController::class);
        Route::get('/all-position', [PositionController::class, 'all']);
    });
    // ────── Approvals ──────
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
    });
    // ────── Projects ──────
    Route::prefix('projects')->group(function () {
        Route::resource('resource', ProjectController::class);
        Route::get('live', [ProjectController::class, 'getLiveProjects']);
        Route::get('owned', [ProjectController::class, 'getOwnedProjects']);
        Route::get('tss', [ProjectController::class, 'tssProjects']);
        Route::patch('{project}/status', [ProjectStatusController::class, 'updateStatus']);
        Route::patch('{project}/update-stage', [ProjectController::class, 'updateStage']);
        Route::post('{project}/archive', [ProjectStatusController::class, 'archive']);
        Route::post('{project}/complete', [ProjectStatusController::class, 'complete']);
        Route::post('replicate', [ProjectController::class, 'replicate']);
        Route::post('{project}/attachments', [ProjectAttachmentController::class, 'store']);
        Route::get('{project}/document-viewer', [ProjectAttachmentController::class, 'getDocumentViewerLink']);
        Route::post('change-summary-rates', [ProjectController::class, 'changeSummaryRates']);
        Route::patch('{project}/cash-flow', [ProjectController::class, 'updateCashFlow']);
        Route::get('{project}/revisions', [RevisionController::class, 'showProjectRevisions']);
        Route::put('{project}/revert/{revision}', [RevisionController::class, 'revertToRevision']);
    });
    // ────── Attachments ──────
    Route::prefix('attachments')->group(function () {
        Route::delete('{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);
    });
    // ────── Phases, Tasks, Resources ──────
    Route::resource('signatures', SetupDocumentSignatureController::class);
    Route::resource('phases', BoqPartController::class);
    Route::resource('tasks', BoqItemController::class);
    Route::prefix('uom')->as('uom.')->group(function () {
        Route::resource('resource', UomController::class);
        Route::get('all', [UomController::class, 'all']);
    });
    Route::prefix('nature-of-work')->as('nature-of-work.')->group(function () {
        Route::resource('resource', NatureOfWorkController::class);
        Route::get('all', [NatureOfWorkController::class, 'all']);
    });
    Route::resource('resource-items', ResourceItemController::class);
    Route::resource('direct-cost-estimates', DirectCostEstimateController::class);
    Route::resource('resource-metrics', ResourceMetricController::class);
    Route::resource('task-schedule', TaskScheduleController::class);
    Route::patch('task-schedule/{id}/schedule', [TaskScheduleController::class, 'updateTaskSchedule']);
    Route::get('/projects/task_schedules', [TaskScheduleController::class, 'filterProjectTaskSchedules']);
    Route::get('bill-of-materials/{item_id}/resources/all', [ResourceItemController::class, 'billOfMaterialsResources']);
    // ────── Revisions ──────
    Route::prefix('project-revisions')->group(function () {
        Route::resource('revisions', RevisionController::class);
        Route::post('revision/{revision}/copy-to-project', [RevisionController::class, 'copyAwardedProjectAsDraft']);
        Route::post('change-to-proposal', [RevisionController::class, 'changeToProposal']);
        Route::post('return-to-draft', [RevisionController::class, 'returnToDraft']);
    });
    // ────── Roles & Permissions ──────
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    // ────── Logs ──────
    Route::resource('logs', LogController::class);
    // ────── Employees ──────
    Route::resource('employees', EmployeeController::class);
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
Route::prefix('artisan')->group(function () {
    Route::get('storage', function () {
        Artisan::call("storage:link");
        return "success";
    });
    Route::get('optimize', function () {
        Artisan::call("optimize");
        return "success";
    });
    Route::get('optimize-clear', function () {
        Artisan::call("optimize:clear");
        return "success";
    });
    // Route::get('custom/{command}', function ($command) {
    //     Artisan::call($command);
    //     return "success";
    // });
});
