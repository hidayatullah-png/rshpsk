<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdministrator
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan hanya user dengan role_id = 1 (Administrator)
     * yang bisa mengakses route tertentu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1️⃣ Jika user belum login → arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2️⃣ Ambil role user dari session (diset saat login)
        $userRole = session('user_role');

        // 3️⃣ Cek apakah role ID sesuai dengan Administrator (1)
        if ($userRole == 1) {
            // Jika iya → izinkan lanjut ke halaman berikutnya
            return $next($request);
        }

        // 4️⃣ Jika bukan Administrator → tolak akses
        return back()->with(
            'error',
            'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.'
        );
    }
}
