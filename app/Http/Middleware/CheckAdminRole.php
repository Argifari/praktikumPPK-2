<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan rolenya adalah admin
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request); // Silakan masuk
        }

        // Kalau bukan admin, tolak akses
        return response()->json([
            'message' => 'Akses ditolak. Anda bukan Admin!'
        ], 403);
    }
}