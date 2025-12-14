<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // ⚠️ Wajib import Carbon

class KategoriKlinisController extends Controller
{
    /** 🟢 Menampilkan daftar kategori (Aktif & Sampah) */
    public function index(Request $request)
    {
        $query = DB::table('kategori_klinis')
            ->select('idkategori_klinis', 'nama_kategori_klinis', 'deleted_at');

        // Cek Mode Sampah
        if ($request->has('trash') && $request->trash == 1) {
            $query->whereNotNull('deleted_at'); // Data Terhapus
        } else {
            $query->whereNull('deleted_at'); // Data Aktif
        }

        $kategoriKlinis = $query->orderBy('nama_kategori_klinis', 'asc')->get();

        return view('dashboard.admin.kategori-klinis.index', compact('kategoriKlinis'));
    }

    /** 🟡 Form tambah kategori klinis baru */
    public function create()
    {
        return view('dashboard.admin.kategori-klinis.create');
    }

    /** 🟢 Simpan kategori klinis baru */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:100|unique:kategori_klinis,nama_kategori_klinis',
        ], [
            'nama_kategori_klinis.unique' => 'Nama kategori klinis sudah ada.'
        ]);

        DB::table('kategori_klinis')->insert([
            'nama_kategori_klinis' => $request->nama_kategori_klinis,
        ]);

        return redirect()
            ->route('dashboard.admin.kategori-klinis.index')
            ->with('success', '✅ Kategori Klinis berhasil ditambahkan!');
    }

    /** 🟣 Detail kategori klinis (Opsional) */
    public function show($id)
    {
        $kategori = DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->first(); // Show bisa menampilkan data aktif/non-aktif, tergantung kebijakan

        if (!$kategori) {
            return redirect()->route('dashboard.admin.kategori-klinis.index');
        }

        return view('dashboard.admin.kategori-klinis.show', compact('kategori'));
    }

    /** ✏️ Form edit kategori klinis */
    public function edit($id)
    {
        // Hanya edit data aktif
        $kategori = DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$kategori) {
            return redirect()
                ->route('dashboard.admin.kategori-klinis.index')
                ->with('danger', '❌ Data tidak ditemukan atau sudah dihapus.');
        }

        return view('dashboard.admin.kategori-klinis.edit', compact('kategori'));
    }

    /** 🔄 Update kategori klinis */
    public function update(Request $request, $id)
    {
        // Pastikan data aktif sebelum update
        $exists = DB::table('kategori_klinis')->where('idkategori_klinis', $id)->whereNull('deleted_at')->exists();
        if (!$exists) abort(404);

        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:100|unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis',
        ]);

        DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->update([
                'nama_kategori_klinis' => $request->nama_kategori_klinis,
            ]);

        return redirect()
            ->route('dashboard.admin.kategori-klinis.index')
            ->with('success', '✅ Kategori Klinis berhasil diperbarui!');
    }

    /** 🗑️ Soft Delete (Pindah ke Sampah) */
    public function destroy($id)
    {
        // Cek Ketergantungan (Misal: apakah dipakai di tabel 'rekam_medis' atau 'layanan')
        /*
        $used = DB::table('layanan_klinis')->where('idkategori_klinis', $id)->exists();
        if ($used) {
            return back()->with('danger', '⚠️ Kategori sedang digunakan, tidak bisa dihapus.');
        }
        */

        DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()
            ->route('dashboard.admin.kategori-klinis.index')
            ->with('success', '🗑️ Kategori Klinis dipindahkan ke sampah!');
    }

    /** ♻️ Restore (Pulihkan Data) */
    public function restore($id)
    {
        DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->update(['deleted_at' => null]);

        return redirect()
            ->route('dashboard.admin.kategori-klinis.index', ['trash' => 1])
            ->with('success', '♻️ Kategori Klinis berhasil dipulihkan!');
    }
}