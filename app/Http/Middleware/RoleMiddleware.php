<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login atau belum
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role (jabatan) user sesuai dengan yang diminta di rute
        if (Auth::user()->role !== $role) {
            // Jika bukan admin maksa masuk halaman admin, tendang dengan error 403
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk masuk ke halaman ini.');
        }

        // 3. Jika aman, persilakan masuk
        return $next($request);
    }
}