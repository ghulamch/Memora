<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::query()->recent();

        // Filter by time range (hour)
        if ($request->has('start_hour') && $request->start_hour !== '') {
            $startHour = (int) $request->start_hour;
            $query->whereTime('created_at', '>=', sprintf('%02d:00:00', $startHour));
        }

        if ($request->has('end_hour') && $request->end_hour !== '') {
            $endHour = (int) $request->end_hour;
            $query->whereTime('created_at', '<=', sprintf('%02d:59:59', $endHour));
        }

        // Filter by session code
        if ($request->has('session_code') && $request->session_code) {
            $query->where('session_code', $request->session_code);
        }

        $photos = $query->get();
        
        // Get session codes for filter
        $sessionCodes = Photo::select('session_code')
            ->distinct()
            ->orderBy('session_code', 'desc')
            ->pluck('session_code');

        // Get statistics
        $stats = [
            'total_photos' => Photo::count(),
            'total_sessions' => Photo::select('session_code')->distinct()->count(),
            'photos_today' => Photo::whereDate('created_at', today())->count(),
            'filtered_count' => $photos->count(),
        ];

        return view('gallery', compact('photos', 'sessionCodes', 'stats'));
    }
}