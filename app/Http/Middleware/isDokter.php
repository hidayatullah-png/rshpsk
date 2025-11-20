<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDokter
{
    /**
     * Middleware ini memastikan hanya user dengan role_id = 2 (Dokter)
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

        // ✅ 3. Cek apakah role ID sesuai dengan Dokter (2)
        if ((int) $userRole === 2) {
            // Jika iya → izinkan lanjut ke halaman berikutnya
            return $next($request);
        }

        // ✅ 4. Jika bukan Dokter → tolak akses dan arahkan ke dashboard utama
        return redirect()->route('dashboard.admin.dashboard-admin')
            ->with('error', 'Akses ditolak. Halaman ini hanya untuk Dokter.');
    }
}
