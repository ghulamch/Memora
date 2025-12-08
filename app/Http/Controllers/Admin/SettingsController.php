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
            'key' => 'required|string',
            'value' => 'required',
        ]);

        $setting = AppSetting::where('key', $request->key)->first();
        
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        // Handle boolean values
        if ($setting->type === 'boolean') {
            $value = filter_var($request->value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        } else {
            $value = $request->value;
        }

        $setting->update(['value' => $value]);

        // Clear cache
        AppSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'setting' => [
                'key' => $setting->key,
                'value' => AppSetting::get($setting->key),
            ]
        ]);
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