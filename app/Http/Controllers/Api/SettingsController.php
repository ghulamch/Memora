<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Get all settings (grouped)
     */
    public function index(Request $request)
    {
        try {
            $group = $request->query('group');
            
            if ($group) {
                // Get settings by group
                $settings = AppSetting::getByGroup($group);
                
                return response()->json([
                    'success' => true,
                    'group' => $group,
                    'data' => $settings,
                ]);
            }
            
            // Get all settings grouped
            $allSettings = AppSetting::all()->groupBy('group');
            $formatted = [];
            
            foreach ($allSettings as $group => $settings) {
                $formatted[$group] = [];
                foreach ($settings as $setting) {
                    $formatted[$group][$setting->key] = [
                        'value' => AppSetting::get($setting->key),
                        'type' => $setting->type,
                        'description' => $setting->description,
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $formatted,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get specific setting
     */
    public function show(string $key)
    {
        try {
            $setting = AppSetting::where('key', $key)->first();
            
            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $setting->key,
                    'value' => AppSetting::get($key),
                    'type' => $setting->type,
                    'group' => $setting->group,
                    'description' => $setting->description,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update setting
     */
    public function update(Request $request, string $key)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'type' => 'nullable|in:string,integer,boolean,json,float,array',
            'group' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Validasi khusus untuk session_gap_minutes
            if ($key === 'session_gap_minutes') {
                $value = (int) $request->value;
                if ($value < 1 || $value > 60) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Session gap harus antara 1-60 menit',
                    ], 422);
                }
            }

            // Validasi khusus untuk max_upload_size_mb
            if ($key === 'max_upload_size_mb') {
                $value = (int) $request->value;
                if ($value < 1 || $value > 100) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Max upload size harus antara 1-100 MB',
                    ], 422);
                }
            }

            $type = $request->input('type', 'string');
            $group = $request->input('group', 'general');
            $description = $request->input('description');
            
            $setting = AppSetting::set($key, $request->value, $type, $group, $description);

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'data' => [
                    'key' => $key,
                    'value' => AppSetting::get($key),
                    'type' => $type,
                    'group' => $group,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch update settings
     */
    public function batchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.type' => 'nullable|in:string,integer,boolean,json,float,array',
            'settings.*.group' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $results = [];
            
            foreach ($request->settings as $setting) {
                $key = $setting['key'];
                $value = $setting['value'];
                $type = $setting['type'] ?? 'string';
                $group = $setting['group'] ?? 'general';
                
                AppSetting::set($key, $value, $type, $group);
                
                $results[$key] = AppSetting::get($key);
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get settings by group
     */
    public function getByGroup(string $group)
    {
        try {
            $settings = AppSetting::getByGroup($group);
            
            if (empty($settings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No settings found in this group',
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'group' => $group,
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        try {
            AppSetting::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Settings cache cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete setting
     */
    public function destroy(string $key)
    {
        try {
            $setting = AppSetting::where('key', $key)->first();
            
            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }
            
            $setting->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}