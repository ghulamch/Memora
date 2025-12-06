<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Template;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalPhotos' => Photo::count(),
            'totalSessions' => Photo::distinct('session_code')->count('session_code'),
            'activeSessions' => Photo::whereDate('created_at', Carbon::today())->distinct('session_code')->count('session_code'),
            'totalTemplates' => Template::count(),
            'activeTemplates' => Template::active()->count(),
        ];

        // Chart data for last 7 days
        $chartData = $this->getChartData(7);

        // Recent activities
        $recentPhotos = Photo::recent()->take(5)->get();
        $recentActivities = $recentPhotos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'type' => 'upload',
                'icon' => 'fas fa-upload',
                'description' => "Foto baru diunggah ke sesi {$photo->session_code}",
                'time' => $photo->created_at->diffForHumans(),
                'count' => null,
            ];
        });

        return view('admin.dashboard', compact('stats', 'chartData', 'recentActivities'));
    }

    private function getChartData($days)
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = Photo::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
