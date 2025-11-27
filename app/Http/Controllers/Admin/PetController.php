<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    /** Helper pesan redirect */
    private function redirectMsg($route, $msg, $type = 'success')
    {
        return redirect()->route($route)->with($type, $msg);
    }

    /** 🔹 API: Ambil Ras berdasarkan Jenis Hewan (Untuk AJAX Dropdown) */
    public function getRasByJenis($idJenis)
    {
        $ras = RasHewan::where('idjenis_hewan', $idJenis)
            ->orderBy('nama_ras')
            ->get();
        return response()->json($ras);
    }

    /** 🔹 Tampilkan semua data Pet */
    public function index()
    {
        try {
            $pets = Pet::with(['pemilik.user', 'ras.jenis'])
                ->orderBy('nama')
                ->get();

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
            // Ambil Pemilik (Join ke User untuk sorting nama)
            $pemilikList = Pemilik::with('user')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->orderBy('user.nama')
                ->select('pemilik.*')
                ->get();

            // Ambil Jenis Hewan
            $jenisList = DB::table('jenis_hewan')->orderBy('nama_jenis_hewan')->get();

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
            Pet::create($r->all()); // Bisa pakai all() karena nama field form = nama kolom DB

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
            $pet = Pet::with(['ras'])->findOrFail($id);

            $pemilikList = Pemilik::with('user')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->orderBy('user.nama')
                ->select('pemilik.*')
                ->get();

            $jenisList = DB::table('jenis_hewan')->orderBy('nama_jenis_hewan')->get();

            // Logic untuk Dropdown Berantai (Jenis -> Ras)
            $currentJenisId = $pet->ras ? $pet->ras->idjenis_hewan : null;
            $rasList = [];
            if ($currentJenisId) {
                $rasList = RasHewan::where('idjenis_hewan', $currentJenisId)
                    ->orderBy('nama_ras')
                    ->get();
            }

            return view('dashboard.admin.pet.edit', compact('pet', 'pemilikList', 'jenisList', 'rasList', 'currentJenisId'));
        } catch (\Throwable $e) {
            return back()->with('danger', 'Data tidak ditemukan.');
        }
    }

    /** 🔹 Update data */
    public function update(Request $r, $id)
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
            $pet = Pet::findOrFail($id);
            $pet->update($r->all());

            return $this->redirectMsg('dashboard.admin.pet.index', '✅ Data Pet berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('Update pet error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data.');
        }
    }

    /** 🔹 Hapus data (AMAN & FINAL) */
    public function destroy($id)
    {
        try {
            // 1. Cek relasi
            $pet = Pet::withCount(['rekamMedis', 'temuDokter'])->findOrFail($id);

            // 2. Validasi Rekam Medis
            if ($pet->rekam_medis_count > 0) {
                return back()->with('danger', "❌ Gagal! Hewan '{$pet->nama}' memiliki {$pet->rekam_medis_count} riwayat rekam medis. Hapus datanya terlebih dahulu.");
            }

            // 3. Validasi Antrian/Reservasi
            if ($pet->temu_dokter_count > 0) {
                return back()->with('danger', "❌ Gagal! Hewan '{$pet->nama}' masih terdaftar di {$pet->temu_dokter_count} antrian/jadwal dokter.");
            }

            // 4. Hapus jika aman
            $pet->delete();

            return back()->with('success', '🗑️ Data Pet berhasil dihapus.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Pesan ramah user jika ada constraint lain
            if ($e->getCode() == "23000") {
                return back()->with('danger', '❌ Gagal hapus: Data ini masih digunakan di tabel lain.');
            }
            return back()->with('danger', 'Terjadi kesalahan database.');
        } catch (\Throwable $e) {
            Log::error('Delete pet error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }
}