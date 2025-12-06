<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Display the landing page with QR code and latest photos
     */
    public function index(Request $request)
    {
        // Get session code from request or use the latest one
        $sessionCode = $request->input('session_code');
        
        if (!$sessionCode) {
            // Get the most recent session code
            $sessionCode = Photo::select('session_code')
                ->whereNotNull('session_code')
                ->latest()
                ->value('session_code');
        }

        // Get latest 5 photos
        $query = Photo::query()->latest();
        
        if ($sessionCode) {
            $query->where('session_code', $sessionCode);
        }
        
        $latestPhotos = $query
            ->take(5)
            ->get()
            ->map(function($photo) {
                return [
                    'id' => $photo->id,
                    'url' => asset('storage/' . $photo->file_path),
                    'name' => $photo->filename ?? 'Photo',
                    'time' => $photo->created_at->diffForHumans(),
                    'timestamp' => $photo->created_at->toIso8601String()
                ];
            });

        // Get statistics
        $stats = $this->getStats($sessionCode);
        
        // Get last photo ID for polling
        $lastPhotoId = $latestPhotos->first()->id ?? 0;

        return view('landing', [
            'sessionCode' => $sessionCode,
            'photos' => $latestPhotos,
            'stats' => $stats,
            'lastPhotoId' => $lastPhotoId
        ]);
    }

    /**
     * Get photo statistics
     */
    protected function getStats($sessionCode = null)
    {
        $cacheKey = 'landing_stats_' . ($sessionCode ?? 'all');
        
        return Cache::remember($cacheKey, 60, function () use ($sessionCode) {
            $totalQuery = Photo::query();
            $todayQuery = Photo::whereDate('created_at', today());
            $sessionQuery = Photo::query();
            
            if ($sessionCode) {
                $totalQuery->where('session_code', $sessionCode);
                $todayQuery->where('session_code', $sessionCode);
                $sessionQuery->where('session_code', $sessionCode);
            }
            
            return [
                'total' => $totalQuery->count(),
                'today' => $todayQuery->count(),
                'session' => $sessionQuery->count()
            ];
        });
    }

    /**
     * API endpoint for polling latest photos
     */
    public function apiLatestPhoto(Request $request)
    {
        $lastPhotoId = $request->input('last_id', 0);
        $sessionCode = $request->input('session_code');

        $query = Photo::where('id', '>', $lastPhotoId)->latest();
        
        if ($sessionCode) {
            $query->where('session_code', $sessionCode);
        }
        
        $photo = $query->first();

        if (!$photo) {
            return response()->json([
                'success' => true,
                'has_new' => false,
                'photo' => null
            ]);
        }

        // Clear cache for stats
        Cache::forget('landing_stats_' . ($sessionCode ?? 'all'));
        
        $stats = $this->getStats($sessionCode);

        return response()->json([
            'success' => true,
            'has_new' => true,
            'photo' => [
                'id' => $photo->id,
                'url' => asset('storage/' . $photo->file_path),
                'name' => $photo->filename ?? 'Photo',
                'time' => $photo->created_at->diffForHumans(),
                'timestamp' => $photo->created_at->toIso8601String()
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Get all available session codes
     */
    public function getSessionCodes()
    {
        $sessions = Photo::select('session_code')
            ->whereNotNull('session_code')
            ->distinct()
            ->orderBy('session_code')
            ->pluck('session_code');

        return response()->json([
            'success' => true,
            'sessions' => $sessions
        ]);
    }

    /**
     * Download QR code as image
     */
    public function downloadQrCode(Request $request)
    {
        $sessionCode = $request->input('session_code');
        $url = route('gallery');
        
        if ($sessionCode) {
            $url .= '?session_code=' . urlencode($sessionCode);
        }
        
        // Redirect to QR code generation service
        return redirect("https://api.qrserver.com/v1/create-qr-code/?size=800x800&data=" . urlencode($url));
    }
}