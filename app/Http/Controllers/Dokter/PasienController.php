<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    /**
     * 🩺 Menampilkan daftar pasien (pemilik dan hewan yang dimiliki)
     */
    public function index()
    {
        $pasien = DB::table('pemilik as pm')
            
            // 🔥 TAMBAHAN: Join ke tabel User untuk ambil Nama & Email Pemilik
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')

            ->leftJoin('pet as p', 'pm.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('ras_hewan as r', 'p.idras_hewan', '=', 'r.idras_hewan')
            ->leftJoin('jenis_hewan as j', 'r.idjenis_hewan', '=', 'j.idjenis_hewan')
            ->select(
                'pm.idpemilik',
                // Ambil Nama & Email dari tabel User (u)
                'u.nama as nama_pemilik',
                'u.email', 
                
                'pm.no_wa',
                'pm.alamat',
                'p.nama as nama_hewan', 
                'j.nama_jenis_hewan',
                'r.nama_ras'
            )
            // Order by nama user
            ->orderBy('u.nama') 
            ->get();

        return view('dashboard.dokter.pasien.index', compact('pasien'));
    }
}