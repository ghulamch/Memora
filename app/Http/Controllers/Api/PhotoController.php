<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PhotoController extends Controller
{
    /**
     * Upload photo dengan AUTO session code generation
     * Session code otomatis dibuat berdasarkan gap waktu upload
     * 
     * POST /api/photos/upload
     * Headers: Authorization: Bearer {token} (optional)
     * Body: 
     *   - photo: file (required)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('photo');
            
            // Generate unique filename
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');

            // AUTO GENERATE SESSION CODE berdasarkan gap waktu
            $sessionCode = $this->generateSessionCode();

            // Create photo record
            $photo = Photo::create([
                'file_path' => $path,
                'session_code' => $sessionCode,
                'original_filename' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'id' => $photo->id,
                    'session_code' => $photo->session_code,
                    'url' => $photo->full_url,
                    'uploaded_at' => $photo->created_at->toISOString(),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate session code otomatis berdasarkan gap waktu
     * 
     * Logic:
     * - Jika foto terakhir < 3 menit yang lalu → gunakan session code yang sama
     * - Jika foto terakhir > 3 menit yang lalu → buat session code baru (increment)
     * - Format: SESSION-YYYYMMDD-001, SESSION-YYYYMMDD-002, dst
     */
    private function generateSessionCode()
    {
        $today = Carbon::today()->format('Ymd');
        $threeMinutesAgo = Carbon::now()->subMinutes(3); // Gap 3 menit

        // Cari foto terakhir hari ini
        $lastPhoto = Photo::whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->first();

        // Jika tidak ada foto hari ini, mulai dari session 1
        if (!$lastPhoto) {
            return "SESSION-{$today}-001";
        }

        // Jika foto terakhir < 3 menit yang lalu, gunakan session code yang sama
        if ($lastPhoto->created_at->greaterThan($threeMinutesAgo)) {
            return $lastPhoto->session_code;
        }

        // Jika foto terakhir > 3 menit yang lalu, buat session code baru
        // Extract nomor session dari session code terakhir
        preg_match('/(\d+)$/', $lastPhoto->session_code, $matches);
        $lastSessionNumber = isset($matches[1]) ? (int)$matches[1] : 0;
        $newSessionNumber = str_pad($lastSessionNumber + 1, 3, '0', STR_PAD_LEFT);

        return "SESI-{$newSessionNumber}";
    }

    /**
     * Bulk upload photos dengan auto session code
     * 
     * POST /api/photos/bulk-upload
     * Headers: Authorization: Bearer {token} (optional)
     * Body: 
     *   - photos[]: files (required)
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|max:10240',
        ]);

        try {
            $uploadedPhotos = [];
            $sessionCode = $this->generateSessionCode(); // Semua foto dalam bulk upload dapat session code yang sama

            foreach ($request->file('photos') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('photos', $filename, 'public');

                $photo = Photo::create([
                    'file_path' => $path,
                    'session_code' => $sessionCode,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);

                $uploadedPhotos[] = [
                    'id' => $photo->id,
                    'session_code' => $photo->session_code,
                    'url' => $photo->full_url,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedPhotos) . ' photos uploaded successfully',
                'data' => $uploadedPhotos,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get photos dengan filter session code DAN rentang jam
     * 
     * GET /api/photos?session_code={code}&start_hour={hour}&end_hour={hour}&date={date}
     * 
     * Parameters:
     * - session_code: string (optional) - Filter by session code
     * - start_hour: integer 0-23 (optional) - Jam mulai (ex: 15 untuk 15:00)
     * - end_hour: integer 0-23 (optional) - Jam akhir (ex: 17 untuk 17:00)
     * - date: date Y-m-d (optional) - Filter by date
     * - per_page: integer (optional) - Default 50
     */
    public function index(Request $request)
    {
        try {
            $query = Photo::query()->orderBy('created_at', 'desc');

            // Filter by session code
            if ($request->has('session_code') && $request->session_code != '') {
                $query->where('session_code', $request->session_code);
            }

            // Filter by rentang jam (start_hour dan end_hour)
            if ($request->has('start_hour') && $request->has('end_hour')) {
                $startHour = (int) $request->start_hour;
                $endHour = (int) $request->end_hour;

                // Filter berdasarkan jam pada created_at
                $query->whereRaw('HOUR(created_at) >= ?', [$startHour])
                      ->whereRaw('HOUR(created_at) <= ?', [$endHour]);
            }

            // Filter by date
            if ($request->has('date') && $request->date != '') {
                $query->whereDate('created_at', $request->date);
            }

            // Pagination
            $perPage = $request->get('per_page', 50);
            $photos = $query->paginate($perPage);

            // Add additional info to each photo
            $photos->getCollection()->transform(function ($photo) {
                return [
                    'id' => $photo->id,
                    'session_code' => $photo->session_code,
                    'url' => $photo->full_url,
                    'original_filename' => $photo->original_filename,
                    'file_size' => $photo->file_size,
                    'formatted_date' => $photo->created_at->format('d M Y'),
                    'formatted_time' => $photo->created_at->format('H:i:s'),
                    'hour' => $photo->created_at->format('H'),
                    'created_at' => $photo->created_at->toISOString(),
                    'updated_at' => $photo->updated_at->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $photos,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch photos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of available session codes untuk filter dropdown
     * 
     * GET /api/photos/session-codes?date={date}
     */
    public function getSessionCodes(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            
            $sessionCodes = Photo::whereDate('created_at', $date)
                ->distinct()
                ->pluck('session_code')
                ->sort()
                ->values();

            return response()->json([
                'success' => true,
                'data' => $sessionCodes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch session codes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics per session code
     * 
     * GET /api/photos/session-stats?date={date}
     */
    public function getSessionStats(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));

            $stats = Photo::whereDate('created_at', $date)
                ->selectRaw('
                    session_code,
                    COUNT(*) as total_photos,
                    MIN(created_at) as first_photo,
                    MAX(created_at) as last_photo
                ')
                ->groupBy('session_code')
                ->orderBy('session_code', 'asc')
                ->get();

            // Format data
            $stats->transform(function ($stat) {
                $firstPhoto = Carbon::parse($stat->first_photo);
                $lastPhoto = Carbon::parse($stat->last_photo);
                
                return [
                    'session_code' => $stat->session_code,
                    'total_photos' => $stat->total_photos,
                    'first_photo_time' => $firstPhoto->format('H:i:s'),
                    'last_photo_time' => $lastPhoto->format('H:i:s'),
                    'duration_minutes' => $firstPhoto->diffInMinutes($lastPhoto),
                    'duration_text' => $firstPhoto->diffInMinutes($lastPhoto) . ' menit',
                    'first_photo' => $firstPhoto->toISOString(),
                    'last_photo' => $lastPhoto->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch session stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete photo by ID
     * 
     * DELETE /api/photos/{id}
     */
    public function destroy($id)
    {
        try {
            $photo = Photo::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($photo->file_path)) {
                Storage::disk('public')->delete($photo->file_path);
            }

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete photos by session code
     * 
     * POST /api/photos/bulk-delete-session
     * Body:
     *   - session_code: string (required)
     */
    public function bulkDeleteBySession(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
        ]);

        try {
            $photos = Photo::where('session_code', $request->session_code)->get();
            
            foreach ($photos as $photo) {
                if (Storage::disk('public')->exists($photo->file_path)) {
                    Storage::disk('public')->delete($photo->file_path);
                }
            }

            $deletedCount = Photo::where('session_code', $request->session_code)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} photos deleted successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photos: ' . $e->getMessage()
            ], 500);
        }
    }
}