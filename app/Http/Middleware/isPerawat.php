<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsPerawat
{
    /**
     * Middleware ini memastikan hanya user dengan role_id = 3 (Perawat)
     * yang bisa mengakses route tertentu.
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // ✅ 2. Ambil role user dari session (diset saat login)
        $userRole = session('user_role');

        // ✅ 3. Jika role ID sesuai dengan Perawat (3), izinkan lanjut
        if ((int) $userRole === 3) {
            return $next($request);
        }

        // ✅ 4. Jika bukan Perawat → tolak akses dengan redirect aman
        return redirect()->route('dashboard.admin.dashboard-admin')
            ->with('error', 'Akses ditolak. Halaman ini hanya untuk Perawat.');
    }
}
