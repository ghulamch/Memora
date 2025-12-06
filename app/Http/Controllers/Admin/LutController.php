<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LutController extends Controller
{
    public function index()
    {
        $luts = Lut::latest()->paginate(20);
        return view('admin.luts.index', compact('luts'));
    }

    public function create()
    {
        return view('admin.luts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lut_file' => 'required|file|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ];

            // Upload LUT file
            if ($request->hasFile('lut_file')) {
                $data['file_path'] = $request->file('lut_file')->store('luts', 'public');
            }

            // Upload thumbnail
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('luts/thumbnails', 'public');
            }

            Lut::create($data);

            // Log the creation
            Log::info('LUT created by user', [
                'name' => $request->name,
                'created_by' => auth()->user()->id,  // Assuming you're using auth
                'file_path' => $data['file_path'] ?? null,
                'thumbnail' => $data['thumbnail'] ?? null,
            ]);

            return redirect()->route('admin.luts.index')
                ->with('success', 'LUT berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal membuat LUT: ' . $e->getMessage());
        }
    }

    public function edit(Lut $lut)
    {
        return view('admin.luts.edit', compact('lut'));
    }

    public function update(Request $request, Lut $lut)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lut_file' => 'nullable|file|mimes:cube,3dl|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ];

            // Upload new LUT file
            if ($request->hasFile('lut_file')) {
                // Delete old file
                if ($lut->file_path && Storage::exists($lut->file_path)) {
                    Storage::delete($lut->file_path);
                }
                $data['file_path'] = $request->file('lut_file')->store('luts', 'public');
            }

            // Upload new thumbnail
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($lut->thumbnail && Storage::exists($lut->thumbnail)) {
                    Storage::delete($lut->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('luts/thumbnails', 'public');
            }

            $lut->update($data);

            // Log the update
            Log::info('LUT updated by user', [
                'lut_id' => $lut->id,
                'updated_by' => auth()->user()->id,  // Assuming you're using auth
                'new_name' => $request->name,
                'file_path' => $data['file_path'] ?? null,
                'thumbnail' => $data['thumbnail'] ?? null,
            ]);

            return redirect()->route('admin.luts.index')
                ->with('success', 'LUT berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal mengupdate LUT: ' . $e->getMessage());
        }
    }


    public function destroy(Lut $lut)
    {
        try {
            // Delete files
            if ($lut->file_path && Storage::exists($lut->file_path)) {
                Storage::delete($lut->file_path);
            }
            if ($lut->thumbnail && Storage::exists($lut->thumbnail)) {
                Storage::delete($lut->thumbnail);
            }

            $lut->delete();

            // Log the deletion
            Log::info('LUT deleted by user', [
                'lut_id' => $lut->id,
                'deleted_by' => auth()->user()->id,  // Assuming you're using auth
            ]);

            return redirect()->route('admin.luts.index')
                ->with('success', 'LUT berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.luts.index')
                ->with('error', 'Gagal menghapus LUT: ' . $e->getMessage());
        }
    }


    public function toggle(Lut $lut)
    {
        $lut->update(['is_active' => !$lut->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $lut->is_active,
        ]);
    }
}
