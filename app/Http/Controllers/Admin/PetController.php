<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    /** Helper pesan redirect */
    private function redirectMsg($route, $msg, $type = 'success')
    {
        return redirect()->route($route)->with($type, $msg);
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
            // Ambil semua pemilik beserta relasi user
            // PERBAIKAN: Dibuat konsisten dengan method edit(), menggunakan join dan order by nama user
            $pemilikList = Pemilik::with('user')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->whereHas('user') // pastikan punya user
                ->orderBy('user.nama')
                ->select('pemilik.*') // Ambil semua kolom dari pemilik
                ->get();

            // Ambil semua ras beserta jenis
            $rasList = RasHewan::with('jenis')
                ->orderBy('nama_ras')
                ->get();

            return view('dashboard.admin.pet.create', compact('pemilikList', 'rasList'));
        } catch (\Throwable $e) {
            \Log::error('Gagal membuka form tambah pet: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat form tambah pet.');
        }
    }


    /** 🔹 Simpan data baru */
    public function store(Request $r)
    {
        $r->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:M,F',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
        ]);

        try {
            Pet::create([
                'nama' => $r->nama,
                'tanggal_lahir' => $r->tanggal_lahir,
                'warna_tanda' => $r->warna_tanda,
                'jenis_kelamin' => $r->jenis_kelamin,
                'idpemilik' => $r->idpemilik,
                'idras_hewan' => $r->idras_hewan,
            ]);

            return $this->redirectMsg('dashboard.admin.pet.index', '🐶 Data Pet berhasil ditambahkan!');
        } catch (\Throwable $e) {
            Log::error('Insert pet error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menambahkan data pet.');
        }
    }

    /** 🔹 Form edit */
    public function edit($id)
    {
        try {
            $pet = Pet::with(['pemilik.user', 'ras.jenis'])->find($id);
            if (!$pet) {
                return $this->redirectMsg('dashboard.admin.pet.index', 'Data Pet tidak ditemukan.', 'danger');
            }

            $pemilikList = Pemilik::with('user')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->orderBy('user.nama')
                ->select('pemilik.*', 'user.nama as nama_user') // 'nama_user' tidak terpakai, tapi select('pemilik.*') penting
                ->get();

            $rasList = RasHewan::with('jenis')
                ->orderBy('nama_ras')
                ->get();

            return view('dashboard.admin.pet.edit', compact('pet', 'pemilikList', 'rasList'));
        } catch (\Throwable $e) {
            Log::error('Edit pet error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal membuka form edit pet.');
        }
    }

    /** 🔹 Update data */
    public function update(Request $r, $id)
    {
        $r->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:M,F',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
        ]);

        try {
            $pet = Pet::findOrFail($id);
            $pet->update([
                'nama' => $r->nama,
                'tanggal_lahir' => $r->tanggal_lahir,
                'warna_tanda' => $r->warna_tanda,
                'jenis_kelamin' => $r->jenis_kelamin,
                'idpemilik' => $r->idpemilik,
                'idras_hewan' => $r->idras_hewan,
            ]);

            return $this->redirectMsg('dashboard.admin.pet.index', '✅ Data Pet berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('Update pet error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data pet.');
        }
    }

    /** 🔹 Hapus data */
    public function destroy($id)
    {
        try {
            $pet = Pet::with('rekamMedis')->findOrFail($id);

            // PERBAIKAN: Cek relasi rekamMedis
            if ($pet->rekamMedis()->exists()) {
 return back()->with('danger', 'Tidak bisa dihapus. Pet masih memiliki data rekam medis terkait.');
            }
            
            $pet->delete();

            return back()->with('success', '🗑️ Data Pet berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Delete pet error: ' . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data pet.');
        }
    }
}