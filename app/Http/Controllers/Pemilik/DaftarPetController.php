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
        // 1. Ambil data pemilik berdasarkan user yang login
        $pemilik = DB::table('pemilik')
            ->where('iduser', Auth::user()->iduser) // Asumsi relasi user->pemilik pakai iduser
            ->first();

        // Jika user belum terdaftar sebagai pemilik (data profil belum lengkap)
        if (!$pemilik) {
            return view('dashboard.pemilik.daftar-pet.index', ['pets' => collect()]);
        }

        // 2. Ambil data hewan milik user tersebut
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
        // Ambil daftar Ras untuk dropdown (Join ke Jenis Hewan agar jelas)
        $ras = DB::table('ras_hewan')
            ->join('jenis_hewan as j', 'j.idjenis_hewan', '=', 'ras_hewan.idjenis_hewan')
            ->select('ras_hewan.idras_hewan', 'ras_hewan.nama_ras', 'j.nama_jenis_hewan')
            ->orderBy('j.nama_jenis_hewan')
            ->orderBy('ras_hewan.nama_ras')
            ->get();

        return view('dashboard.pemilik.daftar-pet.create', compact('ras'));
    }

    // ==========================
    // STORE (Proses simpan data baru)
    // ==========================
    public function store(Request $request)
    {
        $this->validatePet($request);
        
        // Ambil ID Pemilik dari User Login
        $pemilik = DB::table('pemilik')->where('iduser', Auth::user()->iduser)->first();
        if (!$pemilik) {
            return back()->with('error', 'Data profil pemilik belum lengkap. Silakan lengkapi profil terlebih dahulu.');
        }

        $this->insertPet($request, $pemilik->idpemilik);

        return redirect()->route('dashboard.pemilik.daftar-pet.index')
            ->with('success', 'Hewan peliharaan baru berhasil ditambahkan!');
    }

    // ==========================
    // EDIT (Form ubah data)
    // ==========================
    public function edit($id)
    {
        $pemilik = DB::table('pemilik')->where('iduser', Auth::user()->iduser)->first();

        // Pastikan hewan tersebut milik user yang sedang login
        $pet = DB::table('pet')
            ->where('idpet', $id)
            ->where('idpemilik', $pemilik->idpemilik ?? 0)
            ->first();

        if (!$pet) {
            abort(404, 'Data hewan tidak ditemukan atau Anda tidak memiliki akses.');
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
        $pemilik = DB::table('pemilik')->where('iduser', Auth::user()->iduser)->first();
        
        // Validasi kepemilikan sebelum update
        $exists = DB::table('pet')->where('idpet', $id)->where('idpemilik', $pemilik->idpemilik)->exists();
        if (!$exists) abort(403);

        $this->validatePet($request);

        DB::table('pet')
            ->where('idpet', $id)
            ->update([
                'nama' => $this->formatText($request->nama),
                'jenis_kelamin' => $request->jenis_kelamin,
                'idras_hewan' => $request->idras_hewan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'warna_tanda' => $request->warna_tanda, // Tambahan sesuai struktur tabel pet umum
                // 'updated_at' => now(), // Jika tabel pet punya timestamps
            ]);

        return redirect()->route('dashboard.pemilik.daftar-pet.index')
            ->with('success', 'Data hewan berhasil diperbarui!');
    }

    // ==========================
    // DESTROY (Hapus Hewan)
    // ==========================
    public function destroy($id)
    {
        $pemilik = DB::table('pemilik')->where('iduser', Auth::user()->iduser)->first();
        
        // Validasi kepemilikan
        $pet = DB::table('pet')->where('idpet', $id)->where('idpemilik', $pemilik->idpemilik)->first();
        if (!$pet) abort(403);

        // Cek Relasi (Opsional: Cegah hapus jika sudah ada rekam medis)
        $hasMedicalRecord = DB::table('temu_dokter')->where('idpet', $id)->exists();
        if($hasMedicalRecord) {
            return back()->with('error', 'Gagal menghapus! Hewan ini sudah memiliki riwayat pemeriksaan.');
        }

        DB::table('pet')->where('idpet', $id)->delete();

        return back()->with('success', 'Hewan berhasil dihapus.');
    }

    // ==========================
    // VALIDASI INPUT
    // ==========================
    private function validatePet($request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Jantan,Betina', // Sesuaikan dengan enum di DB kamu
            'idras_hewan' => 'required|integer|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama hewan wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'idras_hewan.required' => 'Pilih ras hewan.',
        ]);
    }

    // ==========================
    // HELPER - Insert ke Database
    // ==========================
    private function insertPet($request, $idPemilik)
    {
        DB::table('pet')->insert([
            'idpemilik' => $idPemilik,
            'idras_hewan' => $request->idras_hewan,
            'nama' => $this->formatText($request->nama),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'warna_tanda' => $request->warna_tanda ?? null,
            // 'created_at' => now(), // Jika ada timestamps
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