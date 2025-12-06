<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhotoController as AdminPhotoController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\LutController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('gallery');
});

Route::get('/landing', [LandingController::class, 'index']);
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/editor', [EditorController::class, 'index'])->name('editor');
Route::get('/qr-download', [LandingController::class, 'downloadQrCode'])->name('qr.download');

// Get single latest photo (efficient polling)
Route::get('/api/photos/latest-single', [LandingController::class, 'apiLatestPhoto'])->name('api.photos.latest-single');

// Get available session codes
Route::get('/api/sessions', [LandingController::class, 'getSessionCodes'])->name('api.sessions');


// Admin routes (protected with auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Photos management
    Route::prefix('photos')->name('photos.')->group(function () {
        Route::get('/', [AdminPhotoController::class, 'index'])->name('index');
        Route::delete('/{photo}', [AdminPhotoController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [AdminPhotoController::class, 'bulkDelete'])->name('bulk-delete');
    });
    
    // Templates management
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [TemplateController::class, 'index'])->name('index');
        Route::get('/create', [TemplateController::class, 'create'])->name('create');
        Route::post('/', [TemplateController::class, 'store'])->name('store');
        Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/toggle', [TemplateController::class, 'toggle'])->name('toggle');
    });
     // Tokens Management
    Route::resource('tokens', TokenController::class);
    Route::post('/tokens/{token}/toggle', [TokenController::class, 'toggle'])->name('tokens.toggle');
    Route::post('/tokens/{token}/regenerate', [TokenController::class, 'regenerate'])->name('tokens.regenerate');
    
    // LUTs Management
    Route::resource('luts', LutController::class);
    Route::post('/luts/{lut}/toggle', [LutController::class, 'toggle'])->name('luts.toggle');
});

// Authentication routes (Laravel Breeze/Fortify)
require __DIR__.'/auth.php';
