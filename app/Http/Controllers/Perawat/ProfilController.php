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

        // Query menyesuaikan dengan struktur tabel 'perawat' yang kamu berikan
        $profil = DB::table('user as u')
            // Join ke tabel perawat menggunakan 'iduser' (sesuai schema database)
            ->leftJoin('perawat as p', 'p.iduser', '=', 'u.iduser') 
            
            ->join('role_user as ru', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select(
                'u.nama as nama_user',
                'u.email',
                'r.nama_role',
                
                // Kolom dari tabel perawat
                'p.no_hp',
                'p.jenis_kelamin',
                'p.pendidikan',
                'p.alamat'
            )
            ->where('u.iduser', $user->iduser)
            ->first();

        // Fallback jika data detail perawat belum diisi di database
        if (!$profil) {
            $profil = (object) [
                'nama_user' => $user->nama ?? 'User',
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