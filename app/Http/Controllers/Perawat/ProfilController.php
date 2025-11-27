<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * 🔹 Tampilkan profil perawat yang sedang login
     */
    public function index()
    {
        $user = Auth::user();

        $profil = DB::table('user as u')
            ->leftJoin('perawat as p', 'p.id_user', '=', 'u.iduser') // foreign key benar
            ->join('role_user as ru', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select(
                'u.nama as nama_user',
                'u.email',
                'r.nama_role',
                'p.no_hp',
                'p.jenis_kelamin',
                'p.pendidikan',
                'p.alamat'
            )
            ->where('u.iduser', $user->iduser)
            ->first();

        // fallback jika belum ada data perawat
        if (!$profil) {
            $profil = (object) [
                'nama_user' => $user->nama ?? '-',
                'email' => $user->email ?? '-',
                'nama_role' => 'Perawat',
                'no_hp' => '-',
                'jenis_kelamin' => '-',
                'pendidikan' => '-',
                'alamat' => '-',
            ];
        }

        return view('dashboard.perawat.profil.index', compact('profil'));
    }
}