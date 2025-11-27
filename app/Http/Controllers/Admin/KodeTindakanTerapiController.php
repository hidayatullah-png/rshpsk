<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        $KodeTindakanTerapi = DB::table('kode_tindakan_terapi')
            ->join('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
            ->join('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
            ->select(
                'kode_tindakan_terapi.*',
                // Pastikan 'nama_kategori' sesuai dengan kolom di tabel kategori
                'kategori.nama_kategori as nama_kategori', 
                
                // CEK DATABASE ANDA: Apa nama kolom nama di tabel kategori_klinis?
                // Jika 'nama', ganti jadi 'kategori_klinis.nama'
                // Jika 'nama_kategori_klinis', biarkan seperti ini.
                'kategori_klinis.nama_kategori_klinis as nama_kategori_klinis' 
            )
            ->get();

        return view('dashboard.admin.kode-tindakan-terapi.index', compact('KodeTindakanTerapi'));
    }

    public function create()
    {
        // Ambil data untuk dropdown
        $kategoriList = DB::table('kategori')->get();
        $kategoriKlinisList = DB::table('kategori_klinis')->get();

        return view('dashboard.admin.kode-tindakan-terapi.create', compact('kategoriList', 'kategoriKlinisList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        // Insert data (Tanpa created_at karena tidak ada di schema tabel Anda)
        DB::table('kode_tindakan_terapi')->insert($validated);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode tindakan terapi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tindakan = DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->first();

        if (!$tindakan) {
            abort(404);
        }

        $kategoriList = DB::table('kategori')->get();
        $kategoriKlinisList = DB::table('kategori_klinis')->get();

        return view('dashboard.admin.kode-tindakan-terapi.edit', compact('tindakan', 'kategoriList', 'kategoriKlinisList'));
    }

    public function update(Request $request, $id)
    {
        // Pastikan data ada
        $exists = DB::table('kode_tindakan_terapi')->where('idkode_tindakan_terapi', $id)->exists();
        if (!$exists) {
            abort(404);
        }

        $validated = $request->validate([
            // Pengecualian unique ID harus menggunakan nama kolom primary key yang benar
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->update($validated);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode tindakan terapi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $deleted = DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->delete();

        if ($deleted === 0) {
            abort(404);
        }

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('danger', 'Kode tindakan terapi berhasil dihapus!');
    }
}