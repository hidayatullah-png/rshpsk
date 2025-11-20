<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\KategoriKlinis;

class KodeTindakanTerapiController extends Controller
{
    /**
     * Tampilkan daftar semua kode tindakan terapi.
     */
    public function index()
    {
        $KodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->get();

        return view('dashboard.admin.kode-tindakan-terapi.index', compact('KodeTindakanTerapi'));
    }

    /**
     * Tampilkan form untuk menambahkan kode tindakan terapi baru.
     */
    public function create()
    {
        $kategoriList = Kategori::all();
        $kategoriKlinisList = KategoriKlinis::all();

        return view('dashboard.admin.kode-tindakan-terapi.create', compact('kategoriList', 'kategoriKlinisList'));
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:255',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        KodeTindakanTerapi::create($validated);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode tindakan terapi berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit.
     */
    public function edit($id)
    {
        $tindakan = KodeTindakanTerapi::findOrFail($id);
        $kategoriList = Kategori::all();
        $kategoriKlinisList = KategoriKlinis::all();

        return view('dashboard.admin.kode-tindakan-terapi.edit', compact('tindakan', 'kategoriList', 'kategoriKlinisList'));
    }

    /**
     * Simpan perubahan data.
     */
    public function update(Request $request, $id)
    {
        $tindakan = KodeTindakanTerapi::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kode_tindakan_terapi,kode,' . $tindakan->idkode_tindakan_terapi . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string|max:255',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        $tindakan->update($validated);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode tindakan terapi berhasil diperbarui!');
    }

    /**
     * Hapus data dari database.
     */
    public function destroy($id)
    {
        $tindakan = KodeTindakanTerapi::findOrFail($id);
        $tindakan->delete();

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('danger', 'Kode tindakan terapi berhasil dihapus!');
    }
}
