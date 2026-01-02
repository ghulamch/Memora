<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = AppSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update specific setting
     */
     public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string|exists:app_settings,key',
            'value' => 'required',
        ]);

        try {
            $setting = AppSetting::where('key', $request->key)->firstOrFail();
            
            // Validation khusus per setting
            $value = $request->value;
            
            if ($request->key === 'session_gap_minutes') {
                $value = (int) $value;
                if ($value < 1 || $value > 60) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Session gap must be between 1-60 minutes',
                    ], 422);
                }
            }
            
            if ($request->key === 'max_upload_size_mb') {
                $value = (int) $value;
                if ($value < 1 || $value > 100) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Max upload size must be between 1-100 MB',
                    ], 422);
                }
            }
            
            if ($request->key === 'auto_delete_days') {
                $value = (int) $value;
                if ($value < 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Auto delete days must be 0 or positive',
                    ], 422);
                }
            }
            
            // Update setting
            AppSetting::set($request->key, $value, $setting->type, $setting->group, $setting->description);
            
            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'value' => $value,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Toggle boolean setting
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $setting = AppSetting::where('key', $request->key)
                            ->where('type', 'boolean')
                            ->first();
        
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Boolean setting not found'
            ], 404);
        }

        // Toggle value
        $newValue = $setting->value === '1' ? '0' : '1';
        $setting->update(['value' => $newValue]);

        // Clear cache
        AppSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Setting toggled successfully',
            'setting' => [
                'key' => $setting->key,
                'value' => AppSetting::get($setting->key),
                'enabled' => $newValue === '1',
            ]
        ]);
    }

    /**
     * Get current settings as JSON (for API)
     */
    public function getSettings()
    {
        $settings = AppSetting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => AppSetting::castValue($setting->value, $setting->type)];
        });

        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
}