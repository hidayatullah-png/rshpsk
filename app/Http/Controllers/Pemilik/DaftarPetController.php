<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DaftarPetController extends Controller
{
    // ==========================
    // INDEX (Daftar Pet)
    // ==========================
    public function index()
    {
        $pemilik = DB::table('pemilik')
            ->where('email', Auth::user()->email)
            ->first();

        if (!$pemilik) {
            return view('dashboard.pemilik.daftar-pet.index', ['pets' => collect()]);
        }

        $pets = DB::table('pet')
            ->join('ras_hewan as r', 'r.idras_hewan', '=', 'pet.idras_hewan')
            ->join('jenis_hewan as j', 'j.idjenis_hewan', '=', 'r.idjenis_hewan')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->select('pet.*', 'r.nama_ras', 'j.nama_jenis_hewan')
            ->orderBy('pet.nama')
            ->get();

        return view('dashboard.pemilik.daftar-pet.index', compact('pets'));
    }

    // ==========================
    // CREATE (Form tambah hewan)
    // ==========================
    public function create()
    {
        $ras = DB::table('ras_hewan')
            ->join('jenis_hewan as j', 'j.idjenis_hewan', '=', 'ras_hewan.idjenis_hewan')
            ->select('ras_hewan.idras_hewan', 'ras_hewan.nama_ras', 'j.nama_jenis_hewan')
            ->orderBy('j.nama_jenis_hewan')
            ->get();

        return view('dashboard.pemilik.daftar-pet.create', compact('ras'));
    }

    // ==========================
    // STORE (Proses simpan data baru)
    // ==========================
    public function store(Request $request)
    {
        $this->validatePet($request);
        $this->insertPet($request);

        return redirect()->route('pemilik.pet.index')
            ->with('ok', 'Hewan peliharaan baru berhasil ditambahkan!');
    }

    // ==========================
    // EDIT (Form ubah data)
    // ==========================
    public function edit($id)
    {
        $pemilik = DB::table('pemilik')
            ->where('email', Auth::user()->email)
            ->first();

        $pet = DB::table('pet')
            ->where('idpet', $id)
            ->where('idpemilik', $pemilik->idpemilik)
            ->first();

        if (!$pet) {
            abort(404, 'Data hewan tidak ditemukan.');
        }

        $ras = DB::table('ras_hewan')
            ->join('jenis_hewan as j', 'j.idjenis_hewan', '=', 'ras_hewan.idjenis_hewan')
            ->select('ras_hewan.idras_hewan', 'ras_hewan.nama_ras', 'j.nama_jenis_hewan')
            ->orderBy('j.nama_jenis_hewan')
            ->get();

        return view('dashboard.pemilik.daftar-pet.edit', compact('pet', 'ras'));
    }

    // ==========================
    // UPDATE (Proses simpan perubahan)
    // ==========================
    public function update(Request $request, $id)
    {
        $this->validatePet($request);

        DB::table('pet')
            ->where('idpet', $id)
            ->update([
                'nama' => $this->formatText($request->nama),
                'jenis_kelamin' => $request->jenis_kelamin,
                'idras_hewan' => $request->idras_hewan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'updated_at' => now(),
            ]);

        return redirect()->route('pemilik.pet.index')
            ->with('ok', 'Data hewan berhasil diperbarui!');
    }

    // ==========================
    // VALIDASI INPUT
    // ==========================
    private function validatePet($request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:M,F',
            'idras_hewan' => 'required|integer',
            'tanggal_lahir' => 'nullable|date',
        ], [
            'nama.required' => 'Nama hewan wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'idras_hewan.required' => 'Pilih ras hewan.',
        ]);
    }

    // ==========================
    // HELPER - Insert ke Database
    // ==========================
    private function insertPet($request)
    {
        $pemilik = DB::table('pemilik')
            ->where('email', Auth::user()->email)
            ->first();

        DB::table('pet')->insert([
            'idpemilik' => $pemilik->idpemilik,
            'idras_hewan' => $request->idras_hewan,
            'nama' => $this->formatText($request->nama),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'created_at' => now(),
        ]);
    }

    // ==========================
    // HELPER - Format Teks
    // ==========================
    private function formatText($text)
    {
        return ucfirst(strtolower(trim($text)));
    }
}