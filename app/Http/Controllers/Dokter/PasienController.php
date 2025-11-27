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
        $pasien = DB::table('pemilik')
            ->leftJoin('pet', 'pemilik.idpemilik', '=', 'pet.idpemilik')
            ->leftJoin('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'pemilik.idpemilik',
                'pemilik.nama as nama_pemilik',
                'pemilik.email',
                'pemilik.no_wa',
                'pemilik.alamat',
                'pet.nama as nama_hewan', // ✅ perbaikan di sini
                'jenis_hewan.nama_jenis_hewan',
                'ras_hewan.nama_ras'
            )
            ->orderBy('pemilik.nama')
            ->get();

        return view('dashboard.dokter.pasien.index', compact('pasien'));
    }
}