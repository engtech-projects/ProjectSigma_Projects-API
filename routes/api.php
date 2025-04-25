<?php

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Api\V1\Accessibility\PermissionController;
use App\Http\Controllers\Api\V1\Accessibility\RoleController;
use App\Http\Controllers\Api\V1\Assignment\ProjectAssignmentController;
use App\Http\Controllers\Api\V1\Command\ApiSyncController;
use App\Http\Controllers\Api\V1\Command\SyncEmployees;
use App\Http\Controllers\Api\V1\Command\SyncItemProfiles;
use App\Http\Controllers\Api\V1\Command\SyncSuppliers;
use App\Http\Controllers\Api\V1\Command\SyncUnits;
use App\Http\Controllers\Api\V1\Command\SyncUsers;
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
use App\Http\Resources\User\UserCollection;
use App\Models\ResourceName;
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
Route::middleware('secret_api')->group(function () {
    // SIGMA SERVICES ROUTES
    Route::prefix('sigma')->group(function () {
        // Route::resource('sync-departments', DepartmentsController::class)->names("syncDepartmentsresource");
        // Route::resource('sync-projects', ProjectsController::class)->names("syncProjectsresource");
        Route::get('sync/users', SyncUsers::class);
        Route::get('sync/employees', SyncEmployees::class);
        // Route::resource('sync-employees', EmployeeController::class)->names("syncEmployeeresource");
        // Route::get('suppliers', [RequestSupplierController::class, 'get']);
        Route::get('sync/item-profiles', SyncItemProfiles::class);
        Route::get('sync/suppliers', SyncSuppliers::class);
        Route::get('sync/units', SyncUnits::class);
        // Route::get('uoms', [UOMController::class, 'get']);
    });
});

Route::middleware('auth:api')->group(function () {

    Route::get('/user', function () {
        return response()->json(UserCollection::collection(Auth::user()), 200);
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

    Route::resource('/projects', ProjectController::class);
    Route::get('/original/projects', [ProjectController::class, 'original']);
    Route::get('/revised/projects', [ProjectController::class, 'revised']);
    // project status updates
    Route::post('/projects/{project}/archive', [ProjectStatusController::class, 'archive']);
    Route::post('/projects/{project}/complete', [ProjectStatusController::class, 'complete']);
    Route::patch('/projects/{project}/status', [ProjectStatusController::class, 'updateStatus']);
    // duplicate/clone project
    Route::post('/projects/{project}/replicate', ReplicateProject::class);

    Route::post('/projects/{project}/attachments', [ProjectAttachmentController::class, 'store']);
    Route::delete('/attachments/{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);

    Route::resource('/phases', PhaseController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/resource-items', ResourceItemController::class);

    Route::get('/revisions', [RevisionController::class, 'index']);
    Route::get('/revisions/{revision}', [RevisionController::class, 'show']);
    Route::post('/revisions/{project}/request', [RevisionController::class, 'revise']);
    Route::post('/revisions/{revision}/approve', [RevisionController::class, 'approve']);
    Route::post('/revisions/{revision}/reject', [RevisionController::class, 'reject']);

    // Route::get('/sync/api-data', [ApiSyncController::class, 'sync']);

    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);

    Route::resource('/logs', LogController::class);

    Route::get('/employees', GetAllEmployees::class);
    Route::get('/employee/{employee}', ShowEmployee::class);

    Route::get('/project-assignments/{project}/team', [ProjectAssignmentController::class, 'index']);
    Route::get('/project-assignments/{project_assignment}', [ProjectAssignmentController::class, 'show']);
    Route::post('/project-assignments', [ProjectAssignmentController::class, 'store']);

    Route::resource('/positions', PositionController::class);
});
