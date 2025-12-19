<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * CORS Middleware untuk mengatasi masalah Canvas Tainted
 * 
 * Middleware ini menambahkan headers CORS yang diperlukan agar
 * browser mengizinkan canvas untuk menggunakan gambar dari domain yang sama
 * atau berbeda tanpa menjadi "tainted".
 * 
 * INSTALASI:
 * 1. Simpan file ini di: app/Http/Middleware/Cors.php
 * 2. Register di app/Http/Kernel.php:
 *    - Di $middleware (global) ATAU
 *    - Di $middlewareGroups['web']
 * 
 * Example di Kernel.php:
 * protected $middleware = [
 *     // ...
 *     \App\Http\Middleware\Cors::class,
 * ];
 */
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN');
        }

        // Process the request
        $response = $next($request);

        // Add CORS headers to all responses
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        
        // Cache control untuk static assets
        if ($request->is('storage/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=2592000'); // 30 days
        }

        return $response;
    }
}