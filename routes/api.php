<?php

use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;

use App\Http\Controllers\Api\V1\Project\ {
    ProjectController,
    ProjectStatusController,
    ProjectAttachmentController,
    ReplicateProject,
};

use App\Http\Controllers\Api\V1\Phase\PhaseController;
use App\Http\Controllers\Api\V1\Task\TaskController;
use App\Http\Controllers\Api\V1\ResourceItem\ResourceItemController;

use App\Models\ResourceName;

use Illuminate\Http\Request;
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

    Route::get('/user', function () {
		return response()->json(Auth::user(), 200);
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
    // project status updates
    Route::post('/projects/{project}/archive', [ProjectStatusController::class, 'archive']);
    Route::post('/projects/{project}/complete', [ProjectStatusController::class, 'complete']);
    Route::patch('/projects/{project}/status', [ProjectStatusController::class, 'updateStatus']);
    // duplicate/clone project
    // Route::post('/projects/{project}/clone', [ProjectDuplicateController::class, 'clone']);
    Route::post('/projects/{project}/replicate', ReplicateProject::class);

    Route::post('/projects/{project}/attachments', [ProjectAttachmentController::class, 'store']);
    Route::delete('/attachments/{attachment}/remove', [ProjectAttachmentController::class, 'destroy']);

	Route::resource('/phases', PhaseController::class);
	Route::resource('/tasks', TaskController::class);
    Route::resource('/resource-items', ResourceItemController::class);

});

/* Route::middleware('auth:api')->group(function () {
    Route::group(['prefix'=> 'projects'], function () {
        return response()->json(auth()->user());
    });
});
 */
