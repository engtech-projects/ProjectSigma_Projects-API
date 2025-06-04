<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Debug API Routes
|--------------------------------------------------------------------------
|
| These routes are under the /api/debug prefix and are used to test
| TestController actions and the secret_api middleware.
|
*/

Route::prefix('debug')->group(function () {
    // 1. Test route WITH secret_api middleware
    Route::get('/secret-test', [TestController::class, 'secretTest'])
         ->middleware('secret_api');

    // 2. Test route WITHOUT middleware (to verify controller works)
    Route::get('/no-auth-test', [TestController::class, 'noAuthTest']);

    // 3. Route to show configuration values
    Route::get('/config', [TestController::class, 'showConfig']);

    // 4. Route to show all request headers
    Route::get('/headers', [TestController::class, 'showHeaders']);

    // 5. Route to manually test middleware logic
    Route::get('/manual-auth', [TestController::class, 'manualAuthTest']);
});
// Debug routes for secret_api middleware testing
Route::prefix('debug')->group(function () {
    // 1. Test route WITH secret_api middleware
    Route::get('/secret-test', [App\Http\Controllers\TestController::class, 'secretTest'])
         ->middleware('secret_api');

    // 2. Test route WITHOUT middleware (to verify controller works)
    Route::get('/no-auth-test', [App\Http\Controllers\TestController::class, 'noAuthTest']);

    // 3. Route to show configuration values
    Route::get('/config', [App\Http\Controllers\TestController::class, 'showConfig']);

    // 4. Route to show all request headers
    Route::get('/headers', [App\Http\Controllers\TestController::class, 'showHeaders']);

    // 5. Route to manually test middleware logic
    Route::get('/manual-auth', [App\Http\Controllers\TestController::class, 'manualAuthTest']);
});