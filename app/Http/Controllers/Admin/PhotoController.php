<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::query()->recent();

        if ($request->has('session_code') && $request->session_code) {
            $query->where('session_code', $request->session_code);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $photos = $query->paginate(30);
        $sessionCodes = Photo::select('session_code')->distinct()->orderBy('session_code')->pluck('session_code');

        return view('admin.photos.index', compact('photos', 'sessionCodes'));
    }

    public function destroy(Photo $photo)
    {
        try {
            // Get the correct file path (assuming file_path is relative to 'public/storage')
            $filePath = $photo->file_path; // This should be like 'photos/filename.jpg'

            // Delete the file from storage
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Delete the photo record from the database
            $photo->delete();

            return redirect()->route('admin.photos.index')
                ->with('success', 'Foto berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.photos.index')
                ->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }




    public function bulkDelete(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:photos,id',
        ]);

        try {
            $photos = Photo::whereIn('id', $request->photo_ids)->get();

            foreach ($photos as $photo) {
                if (Storage::exists($photo->file_path)) {
                    Storage::delete($photo->file_path);
                }
                $photo->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($request->photo_ids) . ' foto berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage(),
            ], 500);
        }
    }
}
