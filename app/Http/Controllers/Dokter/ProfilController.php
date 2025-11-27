<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * 👨‍⚕️ Menampilkan profil dokter yang sedang login
     */
    public function index()
    {
        $user = Auth::user();

        // ambil data dokter yang terhubung ke user login
        $profil = DB::table('dokter')
            ->join('user', 'dokter.id_user', '=', 'user.iduser')
            ->select(
                'dokter.id_dokter',
                'user.nama as nama_user',
                'user.email',
                'dokter.bidang_dokter',
                'dokter.no_hp',
                'dokter.jenis_kelamin',
                'dokter.alamat'
            )
            ->where('dokter.id_user', $user->iduser)
            ->first();

        return view('dashboard.dokter.profil.index', compact('profil'));
    }
}