<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IsDokter
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        $role = DB::table('role_user')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->where('role_user.iduser', $user->iduser)
            ->where('role_user.status', 1)
            ->value('role.nama_role');

        if (!$role) {
            abort(403, 'Akses ditolak. Role tidak ditemukan.');
        }

        if (strtolower($role) !== 'dokter') {
            abort(403, 'Akses ditolak. Anda bukan Dokter.');
        }

        return $next($request);
    }
}