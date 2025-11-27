<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use App\Models\User;

class RekamMedisController extends Controller
{
    private function redirectMsg($route, $message, $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }

    /** 🔹 Index */
    public function index(Request $request)
    {
        try {
            $query = RekamMedis::with(['temuDokter.pet.pemilik.user', 'dokter'])
                ->orderBy('created_at', 'desc');

            if ($request->has('filter') && $request->filter == 'today') {
                $query->whereDate('created_at', now());
            }

            $data = $query->paginate(10);

            return view('dashboard.admin.rekam-medis.index', compact('data'));

        } catch (\Throwable $e) {
            Log::error('Gagal load rekam medis: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data rekam medis.');
        }
    }

    /** 🔹 Form Tambah */
    public function create(Request $request)
    {
        $selectedReservasi = null;
        if ($request->has('id_reservasi')) {
            $selectedReservasi = TemuDokter::with('pet.pemilik.user')->find($request->id_reservasi);
        }

        $reservasiList = TemuDokter::with(['pet', 'dokter'])
            ->whereIn('status', ['In-line', 'Pending'])
            ->doesntHave('rekamMedis')
            ->get();

        // PERBAIKAN: Mengambil user dengan idrole = 2 (Dokter)
        $dokter = User::whereHas('roles', fn($q) => $q->where('idrole', 2))->get();

        return view('dashboard.admin.rekam-medis.create', compact('reservasiList', 'dokter', 'selectedReservasi'));
    }

    /** 🔹 Simpan Data (Store) */
    public function store(Request $r)
    {
        $r->validate([
            'idreservasi_dokter' => 'required|integer|exists:temu_dokter,idreservasi_dokter|unique:rekam_medis,idreservasi_dokter',
            'dokter_pemeriksa' => [
                'required',
                'integer',
                'exists:user,iduser',
                // Validasi tambahan: User ID harus punya role dengan idrole 2
                function ($attribute, $value, $fail) {
                    $isDokter = User::where('iduser', $value)
                        ->whereHas('roles', fn($q) => $q->where('idrole', 2))
                        ->exists();
                    if (!$isDokter) {
                        $fail('User yang dipilih bukan dokter.');
                    }
                },
            ],
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'required|string|max:255',
            'terapi' => 'nullable|string|max:255',
        ], [
            'idreservasi_dokter.unique' => 'Reservasi ini sudah memiliki rekam medis.',
        ]);

        DB::beginTransaction();

        try {
            RekamMedis::create([
                'idreservasi_dokter' => $r->idreservasi_dokter,
                'dokter_pemeriksa' => $r->dokter_pemeriksa,
                'anamnesa' => $r->anamnesa,
                'temuan_klinis' => $r->temuan_klinis,
                'diagnosa' => $r->diagnosa,
                'terapi' => $r->terapi,
                'created_at' => now(),
            ]);

            TemuDokter::where('idreservasi_dokter', $r->idreservasi_dokter)
                ->update(['status' => 'Selesai']);

            DB::commit();

            return $this->redirectMsg('dashboard.admin.rekam-medis.index', '✅ Rekam Medis berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Insert RekamMedis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /** 🔹 Form Edit */
    public function edit($id)
    {
        $rekamMedis = RekamMedis::with(['temuDokter.pet', 'dokter'])->findOrFail($id);
        
        // PERBAIKAN: Mengambil user dengan idrole = 2 (Dokter)
        $dokter = User::whereHas('roles', fn($q) => $q->where('idrole', 2))->get();

        return view('dashboard.admin.rekam-medis.edit', [
            'item' => $rekamMedis,
            'dokter' => $dokter,
        ]);
    }

    /** 🔹 Update Data */
    public function update(Request $r, $id)
    {
        $item = RekamMedis::findOrFail($id);

        $r->validate([
            'dokter_pemeriksa' => [
                'required',
                'integer',
                'exists:user,iduser',
                // Validasi tambahan role idrole 2
                function ($attribute, $value, $fail) {
                    $isDokter = User::where('iduser', $value)
                        ->whereHas('roles', fn($q) => $q->where('idrole', 2))
                        ->exists();
                    if (!$isDokter) {
                        $fail('User yang dipilih bukan dokter.');
                    }
                },
            ],
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'required|string|max:255',
            'terapi' => 'nullable|string|max:255',
        ]);

        try {
            $item->update([
                'dokter_pemeriksa' => $r->dokter_pemeriksa,
                'anamnesa' => $r->anamnesa,
                'temuan_klinis' => $r->temuan_klinis,
                'diagnosa' => $r->diagnosa,
                'terapi' => $r->terapi,
            ]);

            return $this->redirectMsg('dashboard.admin.rekam-medis.index', '✅ Data Rekam Medis berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Update RekamMedis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data.');
        }
    }

    /** 🔹 Hapus Data */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $rekamMedis = RekamMedis::with('detailRekamMedis')->findOrFail($id);
            $rekamMedis->detailRekamMedis()->delete();
            $rekamMedis->delete();

            DB::commit();
            return back()->with('success', '🗑️ Data Rekam Medis berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delete RekamMedis error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }
}