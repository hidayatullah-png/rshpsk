<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // ⚠️ Wajib untuk timestamp

class KategoriController extends Controller
{
    /**
     * 🔸 Index: Menampilkan Kategori (Aktif & Sampah)
     */
    public function index(Request $request)
    {
        $query = DB::table('kategori')
            ->select('idkategori', 'nama_kategori', 'deleted_at');

        // Cek mode trash via URL (?trash=1)
        if ($request->has('trash') && $request->trash == 1) {
            $query->whereNotNull('deleted_at'); // Data Sampah
        } else {
            $query->whereNull('deleted_at'); // Data Aktif
        }

        $kategori = $query->orderBy('nama_kategori', 'asc')->get();

        return view('dashboard.admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('dashboard.admin.kategori.create');
    }

    /**
     * 🔸 Store
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.',
        ]);

        // Insert
        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            // 'created_at' => now(), // Jika ada kolom created_at
        ]);

        return redirect()
            ->route('dashboard.admin.kategori.index')
            ->with('success', '✅ Kategori berhasil ditambahkan!');
    }

    /**
     * 🔸 Edit
     */
    public function edit($id)
    {
        // Cari data yang belum dihapus
        $kategori = DB::table('kategori')
            ->where('idkategori', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$kategori) {
            return redirect()
                ->route('dashboard.admin.kategori.index')
                ->with('danger', '❌ Data kategori tidak ditemukan atau sudah dihapus.');
        }

        return view('dashboard.admin.kategori.edit', compact('kategori'));
    }

    /**
     * 🔸 Update
     */
    public function update(Request $request, $id)
    {
        // Pastikan data aktif
        $exists = DB::table('kategori')->where('idkategori', $id)->whereNull('deleted_at')->exists();
        if (!$exists) abort(404);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',idkategori',
        ]);

        DB::table('kategori')->where('idkategori', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            // 'updated_at' => now(),
        ]);

        return redirect()
            ->route('dashboard.admin.kategori.index')
            ->with('success', '✏️ Kategori berhasil diperbarui!');
    }

    /**
     * 🔸 Soft Delete (Pindah ke Sampah)
     */
    public function destroy($id)
    {
        // 1. Cek apakah kategori digunakan di tabel lain (Misal: tabel 'obat' atau 'barang')
        // Sesuaikan 'nama_tabel_barang' dengan tabel kamu yang menggunakan idkategori
        /*
        $used = DB::table('obat')->where('idkategori', $id)->exists();
        if ($used) {
            return back()->with('danger', '⚠️ Tidak dapat dihapus: Kategori masih digunakan pada data Obat/Barang.');
        }
        */

        // 2. Soft Delete (Update deleted_at)
        DB::table('kategori')
            ->where('idkategori', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()
            ->route('dashboard.admin.kategori.index')
            ->with('success', '🗑️ Kategori dipindahkan ke sampah.');
    }

    /**
     * 🔸 Restore (Pulihkan Data)
     */
    public function restore($id)
    {
        DB::table('kategori')
            ->where('idkategori', $id)
            ->update(['deleted_at' => null]);

        return redirect()
            ->route('dashboard.admin.kategori.index', ['trash' => 1])
            ->with('success', '♻️ Kategori berhasil dipulihkan.');
    }
}