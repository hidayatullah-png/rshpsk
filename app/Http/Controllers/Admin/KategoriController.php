<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = DB::table('kategori')
            ->select('idkategori', 'nama_kategori')
            ->orderBy('idkategori')
            ->get();

        return view('dashboard.admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('dashboard.admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('dashboard.admin.kategori.index')
            ->with('success', '✅ Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = DB::table('kategori')->where('idkategori', $id)->first();

        if (!$kategori) {
            return redirect()
                ->route('dashboard.admin.kategori.index')
                ->with('danger', '❌ Data kategori tidak ditemukan.');
        }

        return view('dashboard.admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',idkategori',
        ]);

        DB::table('kategori')->where('idkategori', $id)->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('dashboard.admin.kategori.index')
            ->with('success', '✏️ Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::table('kategori')->where('idkategori', $id)->delete();
            return redirect()
                ->route('dashboard.admin.kategori.index')
                ->with('success', '🗑️ Kategori berhasil dihapus!');
        } catch (\Throwable $e) {
            return redirect()
                ->route('dashboard.admin.kategori.index')
                ->with('danger', '⚠️ Gagal menghapus kategori.');
        }
    }
}
