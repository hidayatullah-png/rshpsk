<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsPemilik
{
    /**
     * Middleware ini memastikan hanya user dengan role_id = 6 (Pemilik)
     * yang bisa mengakses route tertentu.
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ 1. Jika user belum login → arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // ✅ 2. Ambil role user dari session (diset saat login)
        $userRole = session('user_role');

        // ✅ 3. Cek apakah role ID sesuai dengan Pemilik (6)
        if ((int) $userRole === 6) {
            return $next($request);
        }

        // ✅ 4. Jika bukan Pemilik → tolak akses dan arahkan ke halaman aman
        return redirect()->route('dashboard.admin.dashboard-admin')
            ->with('error', 'Akses ditolak. Halaman ini hanya untuk Pemilik.');
    }
}
