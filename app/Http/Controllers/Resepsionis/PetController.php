<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    /** 🔹 Form Tambah Pet */
    public function create(Request $request)
    {
        $jenisList = DB::table('jenis_hewan')
            ->select('idjenis_hewan', 'nama_jenis_hewan')
            ->orderBy('nama_jenis_hewan')
            ->get();

        $rasList = DB::table('ras_hewan')
            ->select('idras_hewan', 'nama_ras')
            ->orderBy('nama_ras')
            ->get();

        $pemilikList = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')->select('pemilik.idpemilik', 'user.nama')
            ->orderBy('nama')
            ->get();

        return view('dashboard.resepsionis.registrasi-pet.create', compact('jenisList', 'rasList', 'pemilikList'));
    }

    /** 🔹 Simpan Pet Baru */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:M,F',
            'idpemilik' => 'required|integer|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|integer|exists:ras_hewan,idras_hewan',
        ]);

        DB::transaction(function () use ($request) {
            DB::table('pet')->insert([
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'warna_tanda' => $request->warna_tanda,
                'jenis_kelamin' => $request->jenis_kelamin,
                'idpemilik' => $request->idpemilik,
                'idras_hewan' => $request->idras_hewan,
            ]);
        });

        return back()->with('success', '🐾 Pet baru berhasil didaftarkan.');
    }
}
