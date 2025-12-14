<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PetController extends Controller
{
    /** 🔹 Helper pesan redirect */
    private function redirectMsg($route, $msg, $type = 'success', $params = [])
    {
        return redirect()->route($route, $params)->with($type, $msg);
    }

    /** 🔹 API: Ambil Ras berdasarkan Jenis Hewan (Untuk AJAX Dropdown) */
    public function getRasByJenis($idJenis)
    {
        $ras = DB::table('ras_hewan')
            ->where('idjenis_hewan', $idJenis)
            ->whereNull('deleted_at') // Hanya ambil ras aktif
            ->orderBy('nama_ras')
            ->get();
            
        return response()->json($ras);
    }

    /** 🔹 Tampilkan semua data Pet (Aktif & Sampah) */
    public function index(Request $request)
    {
        try {
            // Query Dasar dengan Join Lengkap
            $query = DB::table('pet')
                ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser') // Ambil nama pemilik
                ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
                ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
                ->select(
                    'pet.*',
                    'user.nama as nama_pemilik',
                    'ras_hewan.nama_ras',
                    'jenis_hewan.nama_jenis_hewan'
                );

            // Filter Mode Sampah
            if ($request->has('trash') && $request->trash == 1) {
                $query->whereNotNull('pet.deleted_at');
            } else {
                $query->whereNull('pet.deleted_at');
            }

            $pets = $query->orderBy('pet.nama', 'asc')->get();

            return view('dashboard.admin.pet.index', compact('pets'));

        } catch (\Throwable $e) {
            Log::error('Gagal menampilkan pet: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data pet.');
        }
    }

    /** 🔹 Form tambah Pet */
    public function create()
    {
        try {
            // Ambil Pemilik (Join ke User untuk sorting nama) - Hanya Aktif
            $pemilikList = DB::table('pemilik')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->whereNull('pemilik.deleted_at')
                ->whereNull('user.deleted_at')
                ->orderBy('user.nama')
                ->select('pemilik.idpemilik', 'user.nama')
                ->get();

            // Ambil Jenis Hewan - Hanya Aktif
            $jenisList = DB::table('jenis_hewan')
                ->whereNull('deleted_at')
                ->orderBy('nama_jenis_hewan')
                ->get();

            return view('dashboard.admin.pet.create', compact('pemilikList', 'jenisList'));
        } catch (\Throwable $e) {
            Log::error('Gagal form tambah pet: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat form.');
        }
    }

    /** 🔹 Simpan data baru */
    public function store(Request $r)
    {
        $r->validate([
            'nama'          => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda'   => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'idpemilik'     => 'required|exists:pemilik,idpemilik',
            'idras_hewan'   => 'required|exists:ras_hewan,idras_hewan',
        ]);

        try {
            DB::table('pet')->insert([
                'nama'          => $r->nama,
                'tanggal_lahir' => $r->tanggal_lahir,
                'warna_tanda'   => $r->warna_tanda,
                'jenis_kelamin' => $r->jenis_kelamin,
                'idpemilik'     => $r->idpemilik,
                'idras_hewan'   => $r->idras_hewan,
                // 'created_at' => now(), // Uncomment jika ada kolom created_at
            ]);

            return $this->redirectMsg('dashboard.admin.pet.index', '🐶 Data Pet berhasil ditambahkan!');
        } catch (\Throwable $e) {
            Log::error('Insert pet error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data.');
        }
    }

    /** 🔹 Form edit */
    public function edit($id)
    {
        try {
            // Ambil data Pet & Join Ras untuk tahu Jenis Hewan saat ini
            $pet = DB::table('pet')
                ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
                ->where('pet.idpet', $id)
                ->whereNull('pet.deleted_at')
                ->select('pet.*', 'ras_hewan.idjenis_hewan')
                ->first();

            if (!$pet) return back()->with('danger', 'Data tidak ditemukan atau sudah dihapus.');

            // List Pemilik
            $pemilikList = DB::table('pemilik')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->whereNull('pemilik.deleted_at')
                ->orderBy('user.nama')
                ->select('pemilik.idpemilik', 'user.nama')
                ->get();

            // List Jenis Hewan
            $jenisList = DB::table('jenis_hewan')
                ->whereNull('deleted_at')
                ->orderBy('nama_jenis_hewan')
                ->get();

            // Logic Dropdown Berantai (Populate Ras berdasarkan Jenis saat ini)
            $currentJenisId = $pet->idjenis_hewan;
            $rasList = [];
            
            if ($currentJenisId) {
                $rasList = DB::table('ras_hewan')
                    ->where('idjenis_hewan', $currentJenisId)
                    ->whereNull('deleted_at')
                    ->orderBy('nama_ras')
                    ->get();
            }

            return view('dashboard.admin.pet.edit', compact('pet', 'pemilikList', 'jenisList', 'rasList', 'currentJenisId'));
        } catch (\Throwable $e) {
            return back()->with('danger', 'Error memuat data edit.');
        }
    }

    /** 🔹 Update data */
    public function update(Request $r, $id)
    {
        // Pastikan data aktif
        $exists = DB::table('pet')->where('idpet', $id)->whereNull('deleted_at')->exists();
        if (!$exists) abort(404);

        $r->validate([
            'nama'          => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda'   => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'idpemilik'     => 'required|exists:pemilik,idpemilik',
            'idras_hewan'   => 'required|exists:ras_hewan,idras_hewan',
        ]);

        try {
            DB::table('pet')->where('idpet', $id)->update([
                'nama'          => $r->nama,
                'tanggal_lahir' => $r->tanggal_lahir,
                'warna_tanda'   => $r->warna_tanda,
                'jenis_kelamin' => $r->jenis_kelamin,
                'idpemilik'     => $r->idpemilik,
                'idras_hewan'   => $r->idras_hewan,
                // 'updated_at' => now(),
            ]);

            return $this->redirectMsg('dashboard.admin.pet.index', '✅ Data Pet berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('Update pet error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data.');
        }
    }

    /** 🔹 Soft Delete */
    public function destroy($id)
    {
        try {
            $pet = DB::table('pet')->where('idpet', $id)->first();
            if (!$pet) return back()->with('danger', 'Data tidak ditemukan.');

            // 1. Cek Rekam Medis
            // Asumsi tabel rekam_medis menggunakan 'idpet'
            $rmCount = DB::table('rekam_medis')->where('idpet', $id)->count();
            if ($rmCount > 0) {
                return back()->with('danger', "❌ Gagal! Hewan '{$pet->nama}' memiliki {$rmCount} riwayat rekam medis.");
            }

            // 2. Cek Temu Dokter / Reservasi
            // Asumsi tabel temu_dokter menggunakan 'idpet'
            $tdCount = DB::table('temu_dokter')->where('idpet', $id)->count();
            if ($tdCount > 0) {
                return back()->with('danger', "❌ Gagal! Hewan '{$pet->nama}' terdaftar di {$tdCount} jadwal temu dokter.");
            }

            // 3. Lakukan Soft Delete
            DB::table('pet')->where('idpet', $id)->update(['deleted_at' => Carbon::now()]);

            return back()->with('success', '🗑️ Data Pet berhasil dipindahkan ke sampah.');

        } catch (\Throwable $e) {
            Log::error('Delete pet error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }

    /** 🔹 Restore Data */
    public function restore($id)
    {
        try {
            DB::table('pet')->where('idpet', $id)->update(['deleted_at' => null]);
            
            return $this->redirectMsg('dashboard.admin.pet.index', '♻️ Data Pet berhasil dipulihkan.', 'success', ['trash' => 1]);
        } catch (\Throwable $e) {
            return back()->with('danger', 'Gagal memulihkan data.');
        }
    }
}