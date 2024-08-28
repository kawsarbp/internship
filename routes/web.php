<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[DashboardController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // large file upload
    Route::get('upload/large-file-upload', DocumentController::class);
    Route::post('upload/upload-chunk', [DocumentController::class, 'uploadFileChunk'])->name('upload.upload-chunk');

});

Route::group(['middleware' => ['role:user','auth']], function () {
    Route::get('/role',[SiteController::class,'role'])->name('role');
});

Route::get('documents',[DocumentController::class,'index']);
Route::post('document',[DocumentController::class,'store'])->name('document.store');

try {
    Log::info('Document');
}catch (Exception $e) {
    // Log the exception
    Log::error($e->getMessage());
    // Handle the exception or throw a new exception
    // throw new Exception('An error occurred while processing your request');

}

Route::get('test',[SiteController::class,'test']);
require __DIR__.'/auth.php';
