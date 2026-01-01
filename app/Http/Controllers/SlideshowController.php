<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    /**
     * Show slideshow page
     * 
     * IMPORTANT: Only shows SELECTED photos, not all photos in database!
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $query = Photo::query()->recent();

        $photos = $query->get();
        
        return view('slideshow', compact('photos'));
    }
    
    /**
     * Get slideshow by session code
     * 
     * Shows all photos for a specific session
     * 
     * @param string $sessionCode
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function bySession($sessionCode)
    {
        $photos = Photo::where('session_code', $sessionCode)
            ->orderBy('uploaded_at', 'asc')
            ->get();
        
        if ($photos->isEmpty()) {
            return redirect()->route('gallery')
                ->with('error', 'Tidak ada foto untuk session code: ' . $sessionCode);
        }
        
        $photos = $photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'full_url' => $photo->full_url,
                'uploaded_at' => $photo->uploaded_at->format('d M Y, H:i'),
                'session_code' => $photo->session_code,
            ];
        });
        
        return view('slideshow', compact('photos'));
    }
    
    /**
     * Show slideshow for selected photos from gallery
     * Alternative method with clearer naming
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function selected(Request $request)
    {
        return $this->index($request);
    }
}