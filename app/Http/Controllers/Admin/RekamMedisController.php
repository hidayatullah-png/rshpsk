<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\RekamMedis;
use App\Models\DetailRekamMedis;
use App\Models\Pet;
use App\Models\User;

class RekamMedisController extends Controller
{
    /** 🔹 Helper untuk pesan redirect */
    private function redirectMsg($route, $message, $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }

    /** 🔹 Index - tampilkan semua rekam medis */
    public function index()
    {
        try {
            $data = RekamMedis::with(['pet.pemilik.user', 'dokter'])->get();
            return view('dashboard.admin.rekam-medis.index', compact('data'));
        } catch (\Throwable $e) {
            Log::error('Gagal load rekam medis: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data rekam medis.');
        }
    }

    /** 🔹 Form tambah */
    public function create()
    {
        $pet = Pet::with('pemilik')->get();
        $dokter = User::whereHas('roles', fn($q) => $q->where('idrole', 3))->get();

        return view('dashboard.admin.rekam-medis.create', compact('pet', 'dokter'));
    }

    /** 🔹 Simpan data baru */
    public function store(Request $r)
    {
        $r->validate([
            'idreservasi_dokter' => 'required|integer',
            'idpet' => 'required|integer|exists:pet,idpet',
            'dokter_pemeriksa' => 'required|integer|exists:user,iduser',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'required|string|max:255',
            'terapi' => 'nullable|string|max:255',
        ]);

        try {
            RekamMedis::create($r->only([
                'idreservasi_dokter',
                'idpet',
                'dokter_pemeriksa',
                'anamnesa',
                'temuan_klinis',
                'diagnosa',
                'terapi',
            ]));

            return $this->redirectMsg('dashboard.admin.rekam-medis.index', '✅ Rekam Medis berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Insert RekamMedis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data.');
        }
    }

    /** 🔹 Form edit */
    public function edit(RekamMedis $rekamMedis)
    {
        $pet = Pet::with('pemilik')->get();
        $dokter = User::whereHas('roles', fn($q) => $q->where('idrole', 3))->get();

        return view('dashboard.admin.rekam-medis.edit', [
            'item' => $rekamMedis,
            'pet' => $pet,
            'dokter' => $dokter,
        ]);
    }


    /** 🔹 Update data */
    public function update(Request $r, $id)
    {
        $item = RekamMedis::findOrFail($id);
        $r->validate([
            'idpet' => 'required|integer|exists:pet,idpet',
            'dokter_pemeriksa' => 'required|integer|exists:user,iduser',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'required|string|max:255',
            'terapi' => 'nullable|string|max:255',
        ]);

        try {
            $item->update($r->only([
                'idpet',
                'dokter_pemeriksa',
                'anamnesa',
                'temuan_klinis',
                'diagnosa',
                'terapi',
            ]));
            return $this->redirectMsg('dashboard.admin.rekam-medis.index', '✅ Data Rekam Medis berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Update RekamMedis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data.');
        }
    }

    /** 🔹 Hapus data */
    public function destroy($id)
    {
        try {
            $rekamMedis = RekamMedis::with('detailRekamMedis')->findOrFail($id);

            // Hapus semua detail rekam medis terkait
            $rekamMedis->detailRekamMedis()->delete();

            // Hapus rekam medis utama
            RekamMedis::destroy($id);
            return back()->with('success', '🗑️ Data Rekam Medis berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Delete RekamMedis error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }
}