<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentViewerController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(['version' => app()->version()]);
});
Route::get('document-viewer/{cacheKey}', [DocumentViewerController::class, 'showDocumentViewer'])->name('web.document.viewer');
Route::get('artisan-clear-optimization', function () {
    Artisan::call('optimize:clear');
    return 'success';
});
Route::get('/attachments/download/{path}', [DocumentViewerController::class, 'download'])
    ->where('path', '.*')
    ->name('attachments.download');
