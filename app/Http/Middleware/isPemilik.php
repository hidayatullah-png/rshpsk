<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IsPemilik
{
    public function handle($request, Closure $next)
    {
        // cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // ambil user aktif dari Auth
        $currentUser = Auth::user();

        // ambil role aktif
        $role = DB::table('role_user')
            ->join('role', 'role.idrole', '=', 'role_user.idrole')
            ->where('role_user.iduser', $currentUser->iduser)
            ->where('role_user.status', 1)
            ->value('nama_role');

        if (!$role || strtolower($role) !== 'pemilik') {
            abort(403, 'Akses ditolak. Anda bukan Pemilik.');
        }

        return $next($request);
    }
}