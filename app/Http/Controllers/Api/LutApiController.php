<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LutApiController extends Controller
{
    /**
     * Increment usage counter untuk LUT
     * 
     * @param Lut $lut
     * @return \Illuminate\Http\JsonResponse
     */
    public function incrementUsage(Lut $lut)
    {
        try {
            // Increment usage count
            $lut->increment('usage_count');
            
            // Update last_used_at timestamp
            $lut->update(['last_used_at' => now()]);
            
            Log::info('LUT usage incremented', [
                'lut_id' => $lut->id,
                'lut_name' => $lut->name,
                'usage_count' => $lut->usage_count,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usage count incremented',
                'data' => [
                    'usage_count' => $lut->usage_count,
                    'last_used_at' => $lut->last_used_at,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to increment LUT usage', [
                'lut_id' => $lut->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to increment usage count',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all active LUTs
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $luts = Lut::where('is_active', true)
                ->select('id', 'name', 'description', 'file_path', 'thumbnail', 'usage_count')
                ->orderBy('name')
                ->get()
                ->map(function ($lut) {
                    return [
                        'id' => $lut->id,
                        'name' => $lut->name,
                        'description' => $lut->description,
                        'file_url' => $lut->file_path ? asset('storage/' . $lut->file_path) : null,
                        'thumbnail_url' => $lut->thumbnail ? asset('storage/' . $lut->thumbnail) : null,
                        'usage_count' => $lut->usage_count ?? 0,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $luts,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch LUTs', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch LUTs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single LUT details
     * 
     * @param Lut $lut
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lut $lut)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $lut->id,
                    'name' => $lut->name,
                    'description' => $lut->description,
                    'file_url' => $lut->file_path ? asset('storage/' . $lut->file_path) : null,
                    'thumbnail_url' => $lut->thumbnail ? asset('storage/' . $lut->thumbnail) : null,
                    'usage_count' => $lut->usage_count ?? 0,
                    'is_active' => $lut->is_active,
                    'last_used_at' => $lut->last_used_at,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch LUT', [
                'lut_id' => $lut->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch LUT',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get LUT statistics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_luts' => Lut::count(),
                'active_luts' => Lut::where('is_active', true)->count(),
                'total_usage' => Lut::sum('usage_count'),
                'most_used' => Lut::where('is_active', true)
                    ->orderBy('usage_count', 'desc')
                    ->limit(5)
                    ->select('id', 'name', 'usage_count')
                    ->get(),
                'recently_used' => Lut::where('is_active', true)
                    ->whereNotNull('last_used_at')
                    ->orderBy('last_used_at', 'desc')
                    ->limit(5)
                    ->select('id', 'name', 'last_used_at')
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch LUT statistics', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}