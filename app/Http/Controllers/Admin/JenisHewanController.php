<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    /**
     * 🔹 Helper: Validasi input
     */
    protected function validateJenisHewan(Request $request, $id = null)
    {
        // Aturan unik: jika $id ada, maka abaikan record dengan id tersebut
        $uniqueRule = $id
            ? 'unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan'
            : 'unique:jenis_hewan,nama_jenis_hewan';

        return $request->validate([
            'nama_jenis_hewan' => [
                'required',
                'string',
                'max:255',
                'min:3',
                $uniqueRule
            ],
        ]);
    }

    /**
     * 🔹 Format nama
     */
    private function formatNamaJenisHewan($nama)
    {
        return ucwords(strtolower(trim($nama)));
    }

    /**
     * 🔸 Index: tampilkan semua data
     */
    public function index()
    {
        $list = DB::table('jenis_hewan')
            ->orderBy('nama_jenis_hewan')
            ->get();

        return view('dashboard.admin.jenis-hewan.index', compact('list'));
    }

    public function create()
    {
        return view('dashboard.admin.jenis-hewan.create');
    }

    /**
     * 🔸 Store data
     */
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

    /**
     * 🔸 Edit
     */
    public function edit($id)
    {
        $jenis = DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->first();

        return view('dashboard.admin.jenis-hewan.edit', compact('jenis'));
    }

    /**
     * 🔸 Update
     */
    public function update(Request $request, $id)
    {
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
     * 🔸 Destroy
     */
    public function destroy($id)
    {
        // Cek pemakaian di tabel ras
        $used = DB::table('ras_hewan')
            ->where('idjenis_hewan', $id)
            ->exists();

        if ($used) {
            return redirect()->route('dashboard.admin.jenis-hewan.index')
                ->with('danger', '⚠️ Tidak dapat dihapus: masih digunakan pada tabel ras.');
        }

        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->delete();

        return redirect()->route('dashboard.admin.jenis-hewan.index')
            ->with('success', '🗑️ Jenis hewan berhasil dihapus.');
    }
}
