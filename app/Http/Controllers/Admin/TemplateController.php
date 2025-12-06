<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::withCount('slots')->latest()->paginate(12);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'canvas_width' => 'required|integer|min:100',
            'canvas_height' => 'required|integer|min:100',
            'background_image' => 'nullable|image',
            'slots' => 'required|array|min:1',
            'slots.*.x' => 'required|integer',
            'slots.*.y' => 'required|integer',
            'slots.*.width' => 'required|integer|min:50',
            'slots.*.height' => 'required|integer|min:50',
        ]);

        try {
            DB::beginTransaction();

            $templateData = [
                'name' => $request->name,
                'description' => $request->description,
                'canvas_width' => $request->canvas_width,
                'canvas_height' => $request->canvas_height,
                'is_active' => $request->has('is_active'),
            ];

            // Handle background image upload
            if ($request->hasFile('background_image')) {
                $path = $request->file('background_image')->store('templates/backgrounds', 'public');
                $templateData['background_image'] = $path;
            }

            $template = Template::create($templateData);

            // Create slots
            foreach ($request->slots as $index => $slotData) {
                TemplateSlot::create([
                    'template_id' => $template->id,
                    'slot_order' => $index,
                    'x' => $slotData['x'],
                    'y' => $slotData['y'],
                    'width' => $slotData['width'],
                    'height' => $slotData['height'],
                    'rotation' => $slotData['rotation'] ?? 0,
                    'border_style' => $slotData['border_style'] ?? 'none',
                    'border_width' => $slotData['border_width'] ?? 0,
                    'border_color' => $slotData['border_color'] ?? '#000000',
                    'border_radius' => $slotData['border_radius'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    public function edit(Template $template)
    {
        $template->load('slots');
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'canvas_width' => 'required|integer|min:100',
            'canvas_height' => 'required|integer|min:100',
            'background_image' => 'nullable|image',
            'slots' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $templateData = [
                'name' => $request->name,
                'description' => $request->description,
                'canvas_width' => $request->canvas_width,
                'canvas_height' => $request->canvas_height,
                'is_active' => $request->has('is_active'),
            ];

            // Handle background image upload
            if ($request->hasFile('background_image')) {
                // Delete old background
                if ($template->background_image && Storage::exists($template->background_image)) {
                    Storage::delete($template->background_image);
                }
                $path = $request->file('background_image')->store('templates/backgrounds', 'public');
                $templateData['background_image'] = $path;
            }

            $template->update($templateData);

            // Delete old slots
            $template->slots()->delete();

            // Create new slots
            foreach ($request->slots as $index => $slotData) {
                TemplateSlot::create([
                    'template_id' => $template->id,
                    'slot_order' => $index,
                    'x' => $slotData['x'],
                    'y' => $slotData['y'],
                    'width' => $slotData['width'],
                    'height' => $slotData['height'],
                    'rotation' => $slotData['rotation'] ?? 0,
                    'border_style' => $slotData['border_style'] ?? 'none',
                    'border_width' => $slotData['border_width'] ?? 0,
                    'border_color' => $slotData['border_color'] ?? '#000000',
                    'border_radius' => $slotData['border_radius'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal mengupdate template: ' . $e->getMessage());
        }
    }

    public function destroy(Template $template)
    {
        try {
            // Delete associated files
            if ($template->thumbnail && Storage::exists($template->thumbnail)) {
                Storage::delete($template->thumbnail);
            }
            if ($template->background_image && Storage::exists($template->background_image)) {
                Storage::delete($template->background_image);
            }

            $template->delete();

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.templates.index')
                ->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }

    public function toggle(Template $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active,
        ]);
    }
}
