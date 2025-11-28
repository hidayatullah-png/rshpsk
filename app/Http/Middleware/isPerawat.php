<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPerawat
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Mengambil nama role dari tabel role_user -> role
        // Pastikan nama tabel dan kolom sesuai database Anda
        $role = DB::table('role_user')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->where('role_user.iduser', $user->iduser)
            ->where('role_user.status', 1)
            ->value('role.nama_role');

        if (!$role) {
            abort(403, 'Akses ditolak. Role tidak ditemukan.');
        }

        // Cek apakah role adalah perawat
        if (strtolower($role) !== 'perawat') {
            abort(403, 'Akses ditolak. Anda bukan Perawat.');
        }

        return $next($request);
    }
}