<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // ⚠️ Penting untuk timestamp

class JenisHewanController extends Controller
{
    /**
     * 🔹 Helper: Validasi input
     */
    protected function validateJenisHewan(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan'
            : 'unique:jenis_hewan,nama_jenis_hewan';

        return $request->validate([
            'nama_jenis_hewan' => [
                'required', 'string', 'max:255', 'min:3', $uniqueRule
            ],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan wajib diisi.',
            'nama_jenis_hewan.unique'   => 'Nama jenis hewan sudah ada.',
        ]);
    }

    private function formatNamaJenisHewan($nama)
    {
        return ucwords(strtolower(trim($nama)));
    }

    /**
     * 🔸 Index: Menangani Data Aktif & Sampah
     */
    public function index(Request $request)
    {
        $query = DB::table('jenis_hewan');

        // Cek mode trash via URL (?trash=1)
        if ($request->has('trash') && $request->trash == 1) {
            $query->whereNotNull('deleted_at'); // Data Sampah
        } else {
            $query->whereNull('deleted_at'); // Data Aktif
        }

        $list = $query->orderBy('nama_jenis_hewan', 'asc')->get();

        return view('dashboard.admin.jenis-hewan.index', compact('list'));
    }

    public function create()
    {
        return view('dashboard.admin.jenis-hewan.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateJenisHewan($request);
        $nama = $this->formatNamaJenisHewan($data['nama_jenis_hewan']);

        DB::table('jenis_hewan')->insert([
            'nama_jenis_hewan' => $nama
        ]);

        return redirect()->route('dashboard.admin.jenis-hewan.index')
            ->with('success', '✅ Jenis hewan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Pastikan hanya mengedit data yang BELUM dihapus
        $jenis = DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$jenis) {
            return redirect()->route('dashboard.admin.jenis-hewan.index')
                ->with('danger', '⚠️ Data tidak ditemukan atau sudah dihapus.');
        }

        return view('dashboard.admin.jenis-hewan.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        // Cek keberadaan data aktif
        $exists = DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$exists) abort(404);

        $this->validateJenisHewan($request, $id);
        $namaBaru = $this->formatNamaJenisHewan($request->nama_jenis_hewan);

        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->update([
                'nama_jenis_hewan' => $namaBaru
            ]);

        return redirect()->route('dashboard.admin.jenis-hewan.index')
            ->with('success', '✏️ Jenis hewan berhasil diperbarui.');
    }

    /**
     * 🔸 Soft Delete (Pindah ke Sampah)
     */
    public function destroy($id)
    {
        // 1. Cek pemakaian di tabel ras_hewan
        $usedInRas = DB::table('ras_hewan')->where('idjenis_hewan', $id)->exists();

        if ($usedInRas) {
            return back()->with('danger', '⚠️ Tidak dapat dihapus: Masih digunakan pada data Ras Hewan.');
        }

        // 2. Lakukan Soft Delete (Update timestamp)
        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()->route('dashboard.admin.jenis-hewan.index')
            ->with('success', '🗑️ Jenis hewan dipindahkan ke sampah.');
    }

    /**
     * 🔸 Restore (Pulihkan Data)
     */
    public function restore($id)
    {
        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->update(['deleted_at' => null]);

        return redirect()->route('dashboard.admin.jenis-hewan.index', ['trash' => 1])
            ->with('success', '♻️ Jenis hewan berhasil dipulihkan.');
    }
}