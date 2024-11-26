<?php

use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;

use App\Http\Controllers\Api\V1\Project\ {
    ProjectController,
	ProjectDuplicateController,
	ProjectInternalController
};

use App\Http\Controllers\Api\V1\Phase\PhaseController;
use App\Http\Controllers\Api\V1\Task\TaskController;

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
    Route::resource('/projects', ProjectController::class);

	Route::get('/project-status', function () {
		return response()->json(ProjectStatus::cases(), 200);
	});

	Route::get('/project-stage', function () {
		return response()->json(ProjectStage::cases(), 200);
	});

	Route::resource('/phases', PhaseController::class);
	Route::resource('/tasks', TaskController::class);

});

/* Route::middleware('auth:api')->group(function () {
    Route::group(['prefix'=> 'projects'], function () {
        return response()->json(auth()->user());
    });
});
 */
