<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isResepsionis
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan hanya user dengan role_id = 4 (Resepsionis)
     * yang bisa mengakses route tertentu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1️⃣ Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2️⃣ Ambil role user dari session
        $userRole = session('user_role');

        // 3️⃣ Jika role = 4 → izinkan
        if ($userRole == 4) {
            return $next($request);
        }

        // 4️⃣ Jika bukan resepsionis → tolak akses
        return back()->with(
            'error',
            'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.'
        );
    }
}
