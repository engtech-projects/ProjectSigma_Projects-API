<?php

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Actions\Approvals\ApproveApproval;
use App\Http\Controllers\Actions\Approvals\DisapproveApproval;
use App\Http\Controllers\ActivityController;
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
use App\Http\Controllers\BomController;
use App\Http\Controllers\CancelApproval;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DailyScheduleController;
use App\Http\Controllers\DirectCostEstimateController;
use App\Http\Controllers\DirectCostRequestController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NatureOfWorkController;
use App\Http\Controllers\ProjectChangeRequestController;
use App\Http\Controllers\ResourceMetricController;
use App\Http\Controllers\SetupListsController;
use App\Http\Controllers\SetupUomController;
use App\Http\Controllers\TaskScheduleController;
use App\Http\Controllers\TaskScheduleWeeklyController;
use App\Http\Controllers\VoidApproval;
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
                Route::post('/users', [APiSyncController::class, 'syncUsers']);
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
        Route::get('item-profiles', [SetupListsController::class, 'getAllItemProfileList']);
    });
    // ────── Approvals ──────
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
        Route::post('cancel/{modelName}/{model}', CancelApproval::class);
        Route::post('void/{modelName}/{model}', VoidApproval::class);
    });
    // ────── Projects ──────
    Route::prefix('projects')->group(function () {
        // ───── Project's Resource ────
        Route::resource('resource', ProjectController::class);
        Route::patch('resource/{resource}/labor/13th-month', [ResourceItemController::class, 'updateLabor13thMonth']);
        // ───── Live Project ────
        Route::prefix('live')->group(function () {
            Route::get('/', [ProjectController::class, 'getLiveProjects']);
            Route::get('{project}/details', [ProjectController::class, 'getProjectDetails']);
            // ───── Direct Cost - Tss Cashflows ─────
            Route::resource('{project}/cashflows', CashflowController::class);
            Route::post('{project}/cashflows/{cashflow}/restore', [CashflowController::class, 'restore']);
            // ───── Generate Summary Of Estimate Direct Cost ─────
            Route::get('{project}/direct-cost/summary', [ProjectController::class, 'generateSummaryOfDirectEstimate']);
            // ───── Change Requests ─────
            Route::resource('change-requests', ProjectChangeRequestController::class);
            // ───── allRequest, myRequest, myApprovals, ApprovedRequests ─────
            Route::prefix('direct-cost-requests')->group(function () {
                Route::get('/', [DirectCostRequestController::class, 'index']);
                Route::get('all-requests', [DirectCostRequestController::class, 'allRequests']);
                Route::get('my-requests', [DirectCostRequestController::class, 'myRequests']);
                Route::get('my-approvals', [DirectCostRequestController::class, 'myApprovals']);
                Route::get('approved', [DirectCostRequestController::class, 'approved']);
            });
            // ───── Bill of Materials ─────
            Route::get('{project}/bom/generate-bom', [BomController::class, 'generateBillOfMaterials']);
            Route::post('{project}/bom/{bom}/restore', [BomController::class, 'restore']);
            Route::resource('{project}/bom', BomController::class);
            // ───── Project's Data Sheet ─────
            Route::get('{project}/data-sheet', [ProjectController::class, 'getDataSheet']);
        });
        // ───── Project Essentials ────
        Route::get('{project}/resource-items', [ProjectController::class, 'getResourcesItems']);
        Route::get('owned', [ProjectController::class, 'getOwnedProjects']);
        Route::patch('{project}/status', [ProjectStatusController::class, 'updateStatus']);
        Route::patch('{project}/update-stage', [ProjectController::class, 'updateStage']);
        Route::post('{project}/archive', [ProjectStatusController::class, 'archive']);
        Route::post('{project}/complete', [ProjectStatusController::class, 'complete']);
        Route::post('replicate', [ProjectController::class, 'replicate']);
        // ───── Project Attachments ─────
        Route::post('{project}/attachments', [ProjectAttachmentController::class, 'store']);
        Route::get('{project}/document-viewer', [ProjectAttachmentController::class, 'getDocumentViewerLink']);
        // ───── Project Summary Rates ─────
        Route::post('change-summary-rates', [ProjectController::class, 'changeSummaryRates']);
        Route::patch('{project}/cash-flow', [ProjectController::class, 'updateCashFlow']);
        // ───── Project Revisions ────
        Route::get('{project}/revisions', [RevisionController::class, 'showProjectRevisions']);
        Route::post('{project}/tss-revision', [RevisionController::class, 'createTssRevision']);
        Route::put('{project}/revert/{revision}', [RevisionController::class, 'revertToRevision']);
        // ───── Project Activities ────
        Route::get('{project}/activities', [ActivityController::class, 'projectActivities']);
        Route::post('{project}/activities', [ActivityController::class, 'createProjectActivity']);
        // ───── Project Task Schedules ────
        Route::get('{project}/task-schedules', [TaskScheduleController::class, 'getAllTaskScheduleByProject']);
        Route::get('task-schedules', [TaskScheduleController::class, 'filterProjectTaskSchedules']);
        // ───── Project Bill of Quantity ────
        Route::patch('{task}/update-draft-unit-price', [BoqItemController::class, 'updateDraftUnitPrice']);
        // ───── Project Checklist ────
        Route::get('{project}/checklist', [ProjectController::class, 'getProjectChecklist']);
        Route::patch('{project}/checklist/update', [ProjectController::class, 'updateProjectChecklist']);
    });
    // ────── Attachments ──────
    Route::prefix('attachments')->group(function () {
        Route::delete('{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);
    });
    // ────── Phases, Tasks, Resources ──────
    Route::resource('phases', BoqPartController::class);
    Route::post('phases/{phase}/restore', [BoqPartController::class, 'restore']);
    Route::resource('tasks', BoqItemController::class);
    Route::post('tasks/{task}/restore', [BoqItemController::class, 'restore']);
    Route::patch('{task}/update-draft-unit-price', [BoqItemController::class, 'updateDraftUnitPrice']);
    // ────── Task Schedule ──────
    Route::get('task-schedules/{taskSchedule}/weekly', [TaskScheduleWeeklyController::class, 'getWeeklyScheduleByTaskScheduleId']);
    Route::resource('task-schedules/weekly', TaskScheduleWeeklyController::class);
    // ───── Unit of Measurements ────
    Route::prefix('uom')->as('uom.')->group(function () {
        Route::resource('resource', UomController::class);
        Route::get('all', [UomController::class, 'all']);
    });
    // ───── Nature of Work ────
    Route::prefix('nature-of-work')->as('nature-of-work.')->group(function () {
        Route::resource('resource', NatureOfWorkController::class);
        Route::get('all', [NatureOfWorkController::class, 'all']);
    });
    // ───── Resource Items ────
    Route::resource('resource-items', ResourceItemController::class);
    Route::post('resource-items/{resourceItem}/restore', [ResourceItemController::class, 'restore']);
    // ───── Direct Cost Estimates ────
    Route::resource('direct-cost-estimates', DirectCostEstimateController::class);
    Route::post('direct-cost-estimates/{id}/restore', [DirectCostEstimateController::class, 'restore']);
    // ───── Unit of Measurements ────
    Route::resource('resource-metrics', ResourceMetricController::class);
    // ───── Task Schedule ────
    Route::resource('task-schedules', TaskScheduleController::class);
    // ───── Bill of Materials ────
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
    // ────── Activities ──────
    Route::resource('activities', ActivityController::class);
    Route::post('activities/{id}/restore', [ActivityController::class, 'restore']);
    // ────── Daily Schedules ──────
    Route::resource('daily-schedule', DailyScheduleController::class);
    Route::post('daily-schedule/{id}/restore', [DailyScheduleController::class, 'restore']);
    Route::get('activities/{activity}/daily', [DailyScheduleController::class, 'getDailySchedule']);
    Route::post('activities/{activity}/daily', [DailyScheduleController::class, 'updateOrCreateDailySchedule']);
    // ────── Setup Uom ──────
    Route::resource('setup-uom', SetupUomController::class);
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
