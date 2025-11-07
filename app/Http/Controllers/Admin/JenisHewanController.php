<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
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

        // Validasi data input
        return $request->validate([
            'nama_jenis_hewan' => [
                'required',
                'string',
                'max:255',
                'min:3',
                $uniqueRule,
            ],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan wajib diisi.',
            'nama_jenis_hewan.string' => 'Nama jenis hewan harus berupa teks.',
            'nama_jenis_hewan.max' => 'Nama jenis hewan maksimal 255 karakter.',
            'nama_jenis_hewan.min' => 'Nama jenis hewan minimal 3 karakter.',
            'nama_jenis_hewan.unique' => 'Nama jenis hewan sudah ada.',
        ]);
    }
    /**
     * 🔹 Helper: Format nama jenis hewan agar huruf kapital di awal
     */
    private function formatNamaJenisHewan($nama)
    {
        return ucwords(strtolower(trim($nama)));
    }

    /**
     * 🔹 Helper: Simpan data ke database
     */
    private function createJenisHewan($data)
    {
        JenisHewan::create($data);
    }

    /**
     * 🔸 Tampilkan semua data jenis hewan
     */
    public function index()
    {
        $list = JenisHewan::orderBy('nama_jenis_hewan')->get();
        return view('dashboard.admin.jenis-hewan.index', compact('list'));
    }

    /**
     * 🔸 Form tambah jenis hewan
     */
    public function create()
    {
        return view('dashboard.admin.jenis-hewan.create');
    }

    /**
     * 🔸 Simpan jenis hewan baru ke database
     */
    public function store(Request $request)
    {
        // Gunakan helper validasi
        $data = $this->validateJenisHewan($request);

        // Format nama
        $data['nama_jenis_hewan'] = $this->formatNamaJenisHewan($data['nama_jenis_hewan']);

        // Simpan ke database
        $this->createJenisHewan($data);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', '✅ Jenis hewan berhasil ditambahkan.');
    }

    /**
     * 🔸 Tampilkan detail 1 jenis hewan
     */
    public function show($id)
    {
        $jenis = JenisHewan::find($id);

        if (!$jenis) {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('danger', '❌ Jenis hewan tidak ditemukan.');
        }

        return redirect()->route('admin.jenis-hewan.index');
    }

    /**
     * 🔸 Form edit data jenis hewan
     */
    public function edit($id)
    {
        $jenis = JenisHewan::findOrFail($id);
        return view('dashboard.admin.jenis-hewan.edit', compact('jenis'));
    }

    /**
     * 🔸 Update data jenis hewan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis_hewan' => 'required|string|max:100|unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan'
        ]);

        $namaBaru = $this->formatNamaJenisHewan($request->nama_jenis_hewan);

        JenisHewan::where('idjenis_hewan', $id)->update([
            'nama_jenis_hewan' => $namaBaru
        ]);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', '✏️ Jenis hewan berhasil diperbarui.');
    }

    /**
     * 🔸 Hapus data jenis hewan
     */
    public function destroy($id)
    {
        $used = DB::table('ras_hewan')->where('idjenis_hewan', $id)->exists();

        if ($used) {
            return redirect()->route('admin.jenis-hewan.index')
                ->with('danger', '⚠️ Tidak dapat dihapus: masih digunakan pada tabel ras.');
        }

        JenisHewan::where('idjenis_hewan', $id)->delete();

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', '🗑️ Jenis hewan berhasil dihapus.');
    }
}