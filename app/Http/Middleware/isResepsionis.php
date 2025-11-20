<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsResepsionis
{
    /**
     * Middleware memastikan hanya user dengan role_id = 4 (Resepsionis)
     * yang bisa mengakses route tertentu.
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // ✅ 2. Ambil role user dari session (diset saat login)
        $userRoleId = session('user_role');

        // ✅ 3. Jika role cocok → lanjutkan request
        if ((int) $userRoleId === 4) {
            return $next($request);
        }

        // ✅ 4. Jika bukan Resepsionis → tolak akses
        return redirect()->route('dashboard.admin.dashboard-admin')
            ->with('error', 'Akses ditolak. Halaman ini hanya untuk Resepsionis.');
    }
}
