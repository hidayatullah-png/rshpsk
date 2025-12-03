<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /** 
     * 🔹 Tampilkan profil pemilik yang sedang login
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data pemilik berdasarkan email user
        $profil = DB::table('pemilik')
            ->where('email', $user->email)
            ->first();

        // Hitung jumlah hewan yang dimiliki (jika data pemilik ditemukan)
        $jumlahPet = 0;
        if ($profil) {
            $jumlahPet = DB::table('pet')
                ->where('idpemilik', $profil->idpemilik)
                ->count();
        }

        // Jika pemilik belum punya data di tabel pemilik, tampilkan fallback dari tabel user
        if (!$profil) {
            $profil = (object) [
                'nama' => $user->nama,
                'email' => $user->email,
                'no_hp' => '-',
                'alamat' => '-',
            ];
        }

        return view('dashboard.pemilik.profil.index', compact('profil', 'jumlahPet'));
    }
}