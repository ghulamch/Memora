<?php

use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Api\LutApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Photo Booth dengan AUTO Session Code
|--------------------------------------------------------------------------
|
| Session code otomatis dibuat berdasarkan gap waktu upload:
| - Foto dalam 3 menit → Session code yang sama
| - Foto setelah >3 menit → Session code baru (increment)
|
*/

// API routes with token authentication
Route::middleware(['api.token'])->group(function () {
    
    // ========================================
    // UPLOAD ENDPOINTS
    // ========================================
    
    // Upload single photo (AUTO session code generation)
    Route::post('/photos/upload', [PhotoController::class, 'upload']);
    
    // Bulk upload photos (semua foto dapat session code yang sama)
    Route::post('/photos/bulk-upload', [PhotoController::class, 'bulkUpload']);
    
    
    // ========================================
    // GET/LISTING ENDPOINTS
    // ========================================
    
    // Get photos dengan filter session code DAN rentang jam
    // Query params: 
    //   - session_code (optional): Filter by session code
    //   - start_hour (optional, 0-23): Jam mulai (ex: 15 untuk 15:00)
    //   - end_hour (optional, 0-23): Jam akhir (ex: 17 untuk 17:00)
    //   - date (optional, Y-m-d): Filter by date
    //   - per_page (optional, default 50): Items per page
    Route::get('/photos', [PhotoController::class, 'index']);
    
    // Get list session codes (untuk dropdown filter)
    // Query params: 
    //   - date (optional, Y-m-d): Filter by date
    Route::get('/photos/session-codes', [PhotoController::class, 'getSessionCodes']);
    
    // Get statistics per session code
    // Query params: 
    //   - date (optional, Y-m-d): Filter by date
    // Response: total_photos, first_photo_time, last_photo_time, duration
    Route::get('/photos/session-stats', [PhotoController::class, 'getSessionStats']);
    
    
    // ========================================
    // DELETE ENDPOINTS
    // ========================================
    
    // Delete single photo by ID
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);
    
    // Bulk delete by session code
    // Body: { "session_code": "SESSION-20241121-001" }
    Route::post('/photos/bulk-delete-session', [PhotoController::class, 'bulkDeleteBySession']);
    
});

Route::prefix('luts')->name('api.luts.')->group(function () {
    
    // Get all active LUTs
    Route::get('/', [LutApiController::class, 'index'])->name('index');
    
    // Get single LUT
    Route::get('/{lut}', [LutApiController::class, 'show'])->name('show');
    
    // Increment usage count (YANG DIPERLUKAN)
    Route::post('/{lut}/increment-usage', [LutApiController::class, 'incrementUsage'])->name('increment-usage');
    
    // Get statistics
    Route::get('/statistics/summary', [LutApiController::class, 'statistics'])->name('statistics');
});

// Health check (no auth required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'version' => '2.0',
        'features' => [
            'auto_session_code' => true,
            'time_range_filter' => true,
            'session_statistics' => true,
            'bulk_operations' => true,
        ],
        'timestamp' => now()->toISOString(),
    ]);
});


/*
|--------------------------------------------------------------------------
| 📖 Contoh Penggunaan API
|--------------------------------------------------------------------------
|
| Headers untuk semua request (kecuali /health):
|   Authorization: Bearer YOUR_API_TOKEN
|
| 1. Upload Foto (Auto Session Code)
|    POST /api/photos/upload
|    Body: photo (file)
|    Response: { 
|      "data": { 
|        "session_code": "SESSION-20241121-001" 
|      } 
|    }
|
| 2. Get Photos dengan Filter Session Code
|    GET /api/photos?session_code=SESSION-20241121-001
|    Response: List foto dalam session tersebut
|
| 3. Get Photos dengan Filter Rentang Jam
|    GET /api/photos?start_hour=15&end_hour=17
|    Response: List foto antara jam 15:00 - 17:59
|
| 4. Get Photos dengan Filter Kombinasi
|    GET /api/photos?session_code=SESSION-20241121-001&start_hour=15&end_hour=17&date=2024-11-21
|    Response: List foto session tertentu, jam 15-17, tanggal 21 Nov
|
| 5. Get Session Codes untuk Dropdown
|    GET /api/photos/session-codes?date=2024-11-21
|    Response: ["SESSION-20241121-001", "SESSION-20241121-002", ...]
|
| 6. Get Session Statistics
|    GET /api/photos/session-stats?date=2024-11-21
|    Response: [
|      {
|        "session_code": "SESSION-20241121-001",
|        "total_photos": 45,
|        "first_photo_time": "09:00:00",
|        "last_photo_time": "09:15:00",
|        "duration_minutes": 15
|      }
|    ]
|
| 7. Delete Single Photo
|    DELETE /api/photos/123
|    Response: { "success": true }
|
| 8. Bulk Delete by Session Code
|    POST /api/photos/bulk-delete-session
|    Body: { "session_code": "SESSION-20241121-001" }
|    Response: { "message": "45 photos deleted successfully" }
|
*/