<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    /** * 🔹 Tampilkan daftar pasien (pemilik & hewan)
     */
    public function index()
    {
        $pasien = DB::table('pet as p')
            ->join('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            
            // 🔥 TAMBAHAN: Join ke tabel User untuk ambil Nama & Email Pemilik
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')

            ->leftJoin('ras_hewan as r', 'r.idras_hewan', '=', 'p.idras_hewan')
            ->leftJoin('jenis_hewan as j', 'j.idjenis_hewan', '=', 'r.idjenis_hewan')
            ->select(
                'pm.idpemilik',
                
                // Ambil Nama & Email dari tabel User (u), lalu alias-kan
                'u.nama as nama_pemilik', 
                'u.email',
                
                'pm.no_wa',
                'pm.alamat',
                'p.nama as nama_hewan',
                'j.nama_jenis_hewan',
                'r.nama_ras'
            )
            ->orderBy('u.nama') // Order by nama user
            ->get();

        return view('dashboard.perawat.pasien.index', compact('pasien'));
    }
}