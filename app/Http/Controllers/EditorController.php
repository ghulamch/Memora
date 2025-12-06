<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\Lut;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $photoIds = explode(',', $request->input('photos', ''));
        $photos = Photo::whereIn('id', $photoIds)->get();

        if ($photos->isEmpty()) {
            return redirect()->route('gallery')->with('error', 'Silakan pilih foto terlebih dahulu');
        }

        // Load templates dengan frame_url accessor
        $templates = Template::active()->with('slots')->get()->map(function ($template) {
            // Pastikan frame_url ada dan dalam format yang benar
            if ($template->frame_path) {
                $template->frame_url = asset('storage/' . $template->frame_path);
            } else {
                $template->frame_url = null;
            }
            return $template;
        });
        
        $luts = Lut::active()->get();

        return view('editor2', compact('photos', 'templates', 'luts'));
    }
}