<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RasHewanController extends Controller
{
    /**
     * Menampilkan daftar ras hewan (dengan join jenis hewan)
     * Mendukung mode Aktif dan Sampah (Soft Delete)
     */
    public function index(Request $request)
    {
        $query = DB::table('ras_hewan')
            ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'ras_hewan.idras_hewan',
                'ras_hewan.nama_ras',
                'ras_hewan.deleted_at', // Ambil deleted_at untuk ditampilkan di view sampah
                'jenis_hewan.nama_jenis_hewan'
            );

        // Cek mode trash
        if ($request->has('trash') && $request->trash == 1) {
            $query->whereNotNull('ras_hewan.deleted_at'); // Data Sampah
        } else {
            $query->whereNull('ras_hewan.deleted_at'); // Data Aktif
        }

        $rasList = $query
            ->orderBy('jenis_hewan.nama_jenis_hewan')
            ->orderBy('ras_hewan.nama_ras')
            ->get();

        return view('dashboard.admin.ras-hewan.index', compact('rasList'));
    }

    /**
     * Form tambah ras hewan
     */
    public function create()
    {
        // Ambil jenis hewan yang AKTIF saja
        $jenisList = DB::table('jenis_hewan')
            ->whereNull('deleted_at')
            ->orderBy('nama_jenis_hewan')
            ->get();

        return view('dashboard.admin.ras-hewan.create', compact('jenisList'));
    }

    /**
     * Simpan data ras hewan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ras' => 'required|string|max:100|unique:ras_hewan,nama_ras',
            'idjenis_hewan' => 'required|integer|exists:jenis_hewan,idjenis_hewan',
        ], [
            'nama_ras.unique' => 'Nama ras ini sudah ada.',
            'idjenis_hewan.exists' => 'Jenis hewan tidak valid.'
        ]);

        DB::table('ras_hewan')->insert([
            'nama_ras' => $request->nama_ras,
            'idjenis_hewan' => $request->idjenis_hewan,
        ]);

        return redirect()
            ->route('dashboard.admin.ras-hewan.index')
            ->with('success', '✅ Ras hewan berhasil ditambahkan!');
    }

    /**
     * Form edit ras hewan
     */
    public function edit($id)
    {
        // Cari ras yang belum dihapus
        $ras = DB::table('ras_hewan')
            ->where('idras_hewan', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$ras) {
            return redirect()
                ->route('dashboard.admin.ras-hewan.index')
                ->with('danger', '❌ Data ras tidak ditemukan atau sudah dihapus.');
        }

        // Ambil list jenis hewan (Aktif saja)
        $jenisList = DB::table('jenis_hewan')
            ->whereNull('deleted_at')
            ->orderBy('nama_jenis_hewan')
            ->get();

        return view('dashboard.admin.ras-hewan.edit', compact('ras', 'jenisList'));
    }

    /**
     * Update data ras hewan
     */
    public function update(Request $request, $id)
    {
        // Pastikan data ada dan aktif
        $exists = DB::table('ras_hewan')->where('idras_hewan', $id)->whereNull('deleted_at')->exists();
        if (!$exists) abort(404);

        $request->validate([
            'nama_ras' => 'required|string|max:100|unique:ras_hewan,nama_ras,' . $id . ',idras_hewan',
            'idjenis_hewan' => 'required|integer|exists:jenis_hewan,idjenis_hewan',
        ]);

        DB::table('ras_hewan')
            ->where('idras_hewan', $id)
            ->update([
                'nama_ras' => $request->nama_ras,
                'idjenis_hewan' => $request->idjenis_hewan,
            ]);

        return redirect()
            ->route('dashboard.admin.ras-hewan.index')
            ->with('success', '✏️ Data ras hewan berhasil diperbarui!');
    }

    /**
     * Soft Delete: Pindahkan ke sampah
     */
    public function destroy($id)
    {
        // Cek apakah ras digunakan di tabel 'pet' (hewan peliharaan user)
        $used = DB::table('pet')->where('idras_hewan', $id)->exists();
        
        if ($used) {
            return back()->with('danger', '⚠️ Tidak dapat dihapus: Masih digunakan pada data Pet user.');
        }

        // Soft Delete
        DB::table('ras_hewan')
            ->where('idras_hewan', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()
            ->route('dashboard.admin.ras-hewan.index')
            ->with('success', '🗑️ Ras hewan dipindahkan ke sampah.');
    }

    /**
     * Restore: Pulihkan dari sampah
     */
    public function restore($id)
    {
        DB::table('ras_hewan')
            ->where('idras_hewan', $id)
            ->update(['deleted_at' => null]);

        return redirect()
            ->route('dashboard.admin.ras-hewan.index', ['trash' => 1])
            ->with('success', '♻️ Ras hewan berhasil dipulihkan.');
    }
}