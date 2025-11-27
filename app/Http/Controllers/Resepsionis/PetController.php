<?php

namespace App\Http\Controllers\Resepsionis;

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

    /** 🔹 API: Ambil Ras (Sama seperti Admin) */
    public function getRasByJenis($idJenis)
    {
        $ras = RasHewan::where('idjenis_hewan', $idJenis)
            ->orderBy('nama_ras')
            ->get();
        return response()->json($ras);
    }

    /** 🔹 Form Tambah Pet (Resepsionis) */
    public function create()
    {
        try {
            // Ambil Pemilik
            $pemilikList = Pemilik::with('user')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->orderBy('user.nama')
                ->select('pemilik.*')
                ->get();

            // Ambil Jenis Hewan
            $jenisList = DB::table('jenis_hewan')->orderBy('nama_jenis_hewan')->get();

            // Arahkan ke View khusus Resepsionis
            return view('dashboard.resepsionis.registrasi-pet.create', compact('pemilikList', 'jenisList'));
        } catch (\Throwable $e) {
            Log::error('Gagal form tambah pet resepsionis: ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat form.');
        }
    }

    /** 🔹 Simpan Pet Baru */
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
            Pet::create($r->all());

            return $this->redirectMsg('dashboard.resepsionis.registrasi-pet.create', '🐶 Data Pet berhasil didaftarkan!');

        } catch (\Throwable $e) {
            Log::error('Insert pet resepsionis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data.');
        }
    }
}