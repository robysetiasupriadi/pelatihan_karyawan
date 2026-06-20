<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Login tapi role tidak sesuai
        if (!in_array($request->user()->role, $roles)) {
            // Kalau request AJAX/API kembalikan JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
