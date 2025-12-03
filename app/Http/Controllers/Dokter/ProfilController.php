<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * 🔹 Tampilkan profil dokter yang sedang login
     */
    public function index()
    {
        $user = Auth::user();

        $profil = DB::table('user as u')
            // 🔥 PERBAIKAN: Ganti 'd.id_user' menjadi 'd.iduser'
            ->leftJoin('dokter as d', 'd.iduser', '=', 'u.iduser') 
            
            ->join('role_user as ru', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select(
                'u.nama as nama_user',
                'u.email',
                'r.nama_role',
                'd.no_hp',
                'd.jenis_kelamin',
                'd.bidang_dokter', // Asumsi ada kolom ini di tabel dokter
                'd.alamat'
            )
            ->where('u.iduser', $user->iduser)
            ->first();

        // Mengambil data dari tabel dokter (bukan perawat)
        // Jika Anda menggunakan nama kolom 'bidang_dokter' di tabel dokter:
        $role_user = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select('r.nama_role')
            ->where('u.iduser', $user->iduser)
            ->first();


        // Fallback jika data detail dokter belum diisi di database
        if (!$profil) {
            $profil = (object) [
                'nama_user' => $user->nama ?? 'User',
                'email' => $user->email ?? '-',
                // Ambil role dari tabel role_user jika profil dokter kosong
                'nama_role' => $role_user->nama_role ?? 'Dokter',
                'no_hp' => '-',
                'jenis_kelamin' => '-',
                'bidang_dokter' => '-', // Atau 'bidang_dokter'
                'alamat' => '-',
            ];
        }

        return view('dashboard.dokter.profil.index', compact('profil'));
    }
}