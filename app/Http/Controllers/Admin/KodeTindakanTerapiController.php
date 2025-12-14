<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KodeTindakanTerapiController extends Controller
{
    /** 🟢 Index: Menampilkan Data (Aktif & Sampah) */
    public function index(Request $request)
    {
        $query = DB::table('kode_tindakan_terapi')
            ->join('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
            ->join('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
            ->select(
                'kode_tindakan_terapi.*',
                'kategori.nama_kategori as nama_kategori', 
                'kategori_klinis.nama_kategori_klinis as nama_kategori_klinis' 
            );

        // 🔹 Filter Mode Sampah vs Aktif
        if ($request->has('trash') && $request->trash == 1) {
            // Ambil yang terhapus
            $query->whereNotNull('kode_tindakan_terapi.deleted_at');
        } else {
            // Ambil yang aktif
            $query->whereNull('kode_tindakan_terapi.deleted_at');
        }

        $KodeTindakanTerapi = $query->get();

        return view('dashboard.admin.kode-tindakan-terapi.index', compact('KodeTindakanTerapi'));
    }

    /** 🟡 Form Create */
    public function create()
    {
        // Ambil data dropdown (Hanya yang AKTIF)
        // Asumsi tabel kategori dan kategori_klinis juga punya soft delete
        $kategoriList = DB::table('kategori')
            ->whereNull('deleted_at') 
            ->get();

        $kategoriKlinisList = DB::table('kategori_klinis')
            ->whereNull('deleted_at')
            ->get();

        return view('dashboard.admin.kode-tindakan-terapi.create', compact('kategoriList', 'kategoriKlinisList'));
    }

    /** 🟢 Store */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        DB::table('kode_tindakan_terapi')->insert($validated);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', '✅ Kode tindakan terapi berhasil ditambahkan!');
    }

    /** ✏️ Edit */
    public function edit($id)
    {
        // Cari data aktif berdasarkan ID
        $tindakan = DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$tindakan) {
            return redirect()
                ->route('dashboard.admin.kode-tindakan-terapi.index')
                ->with('danger', '❌ Data tidak ditemukan atau sudah dihapus.');
        }

        // Dropdown (Hanya Aktif)
        $kategoriList = DB::table('kategori')->whereNull('deleted_at')->get();
        $kategoriKlinisList = DB::table('kategori_klinis')->whereNull('deleted_at')->get();

        return view('dashboard.admin.kode-tindakan-terapi.edit', compact('tindakan', 'kategoriList', 'kategoriKlinisList'));
    }

    /** 🔄 Update */
    public function update(Request $request, $id)
    {
        // Pastikan data ada dan aktif
        $exists = DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$exists) abort(404);

        $validated = $request->validate([
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
            ->with('success', '✏️ Kode tindakan terapi berhasil diperbarui!');
    }

    /** 🗑️ Soft Delete */
    public function destroy($id)
    {
        // Cek ketergantungan (Misal: dipakai di tabel transaksi/rekam medis)
        /*
        $used = DB::table('detail_tindakan_medis')->where('idkode_tindakan_terapi', $id)->exists();
        if ($used) {
            return back()->with('danger', '⚠️ Tidak dapat dihapus: Kode sedang digunakan dalam transaksi.');
        }
        */

        DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index')
            ->with('success', '🗑️ Kode tindakan terapi dipindahkan ke sampah!');
    }

    /** ♻️ Restore */
    public function restore($id)
    {
        DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->update(['deleted_at' => null]);

        return redirect()
            ->route('dashboard.admin.kode-tindakan-terapi.index', ['trash' => 1])
            ->with('success', '♻️ Kode tindakan terapi berhasil dipulihkan!');
    }
}