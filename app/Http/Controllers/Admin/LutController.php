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
            'lut_file' => [
                'required',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (!in_array($extension, ['cube', '3dl', 'txt'])) {
                        $fail('File harus berformat .cube, .3dl, atau .txt');
                    }
                },
            ],
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ];

            // Upload LUT file dengan ekstensi asli
            if ($request->hasFile('lut_file')) {
                $file = $request->file('lut_file');
                $originalExtension = $file->getClientOriginalExtension();
                
                // Jika file upload tanpa ekstensi atau ekstensi salah, paksa jadi .cube
                if (empty($originalExtension) || !in_array(strtolower($originalExtension), ['cube', '3dl', 'txt'])) {
                    $originalExtension = 'cube';
                }
                
                $filename = uniqid() . '.' . $originalExtension;
                $data['file_path'] = $file->storeAs('luts', $filename, 'public');
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
            'lut_file' => [
                'nullable',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, ['cube', '3dl', 'txt'])) {
                            $fail('File harus berformat .cube, .3dl, atau .txt');
                        }
                    }
                },
            ],
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ];

            // Upload new LUT file dengan ekstensi asli
            if ($request->hasFile('lut_file')) {
                // Delete old file
                if ($lut->file_path && Storage::disk('public')->exists($lut->file_path)) {
                    Storage::disk('public')->delete($lut->file_path);
                }
                
                $file = $request->file('lut_file');
                $originalExtension = $file->getClientOriginalExtension();
                
                // Jika file upload tanpa ekstensi atau ekstensi salah, paksa jadi .cube
                if (empty($originalExtension) || !in_array(strtolower($originalExtension), ['cube', '3dl', 'txt'])) {
                    $originalExtension = 'cube';
                }
                
                $filename = uniqid() . '.' . $originalExtension;
                $data['file_path'] = $file->storeAs('luts', $filename, 'public');
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
            if ($lut->file_path && Storage::disk('public')->exists($lut->file_path)) {
                Storage::disk('public')->delete($lut->file_path);
            }
            if ($lut->thumbnail && Storage::disk('public')->exists($lut->thumbnail)) {
                Storage::disk('public')->delete($lut->thumbnail);
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

    /**
     * Memperbaiki ekstensi file LUT yang salah (.txt atau .tif menjadi .cube)
     * Route: GET /admin/luts/fix-extensions
     */
    public function fixFileExtensions()
    {
        try {
            $luts = Lut::whereNotNull('file_path')->get();
            $fixed = 0;
            $errors = [];

            foreach ($luts as $lut) {
                $oldPath = $lut->file_path;
                
                // Skip jika sudah .cube atau .3dl
                if (preg_match('/\.(cube|3dl)$/i', $oldPath)) {
                    continue;
                }

                // Check apakah file ada
                if (!Storage::disk('public')->exists($oldPath)) {
                    $errors[] = "File tidak ditemukan: {$oldPath}";
                    continue;
                }

                // Baca beberapa baris pertama untuk validasi apakah ini LUT file
                $content = Storage::disk('public')->get($oldPath);
                $firstLines = substr($content, 0, 200);
                
                // Cek apakah ini valid LUT file
                if (!preg_match('/LUT_3D_SIZE|LUT_1D_SIZE/i', $firstLines)) {
                    $errors[] = "Bukan file LUT: {$oldPath}";
                    continue;
                }

                // Generate nama file baru dengan ekstensi .cube
                $newPath = preg_replace('/\.(txt|tif|tiff)$/i', '.cube', $oldPath);
                
                // Jika tidak ada ekstensi, tambahkan .cube
                if ($newPath === $oldPath) {
                    $newPath = $oldPath . '.cube';
                }

                // Rename file
                if (Storage::disk('public')->move($oldPath, $newPath)) {
                    $lut->update(['file_path' => $newPath]);
                    $fixed++;
                    Log::info("LUT file extension fixed", [
                        'lut_id' => $lut->id,
                        'old_path' => $oldPath,
                        'new_path' => $newPath,
                    ]);
                } else {
                    $errors[] = "Gagal rename: {$oldPath} -> {$newPath}";
                }
            }

            $message = "Berhasil memperbaiki {$fixed} file LUT.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return redirect()->route('admin.luts.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Fix LUT extensions failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.luts.index')
                ->with('error', 'Gagal memperbaiki ekstensi file: ' . $e->getMessage());
        }
    }
}