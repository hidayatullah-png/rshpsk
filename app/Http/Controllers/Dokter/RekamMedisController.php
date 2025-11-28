<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RekamMedisController extends Controller
{
    private function redirectMsg($route, $message, $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }

    // ✅ INDEX: Dokter melihat daftar pasien yang sudah ada Rekam Medis-nya
    public function index(Request $request)
    {
        try {
            // Dokter fokus pada Rekam Medis yang sudah ada (siap diisi Tindakan/Resep)
            $query = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->join('user as pem_user', 'pem.iduser', '=', 'pem_user.iduser')
                // Tambahkan Left Join ke user dokter pemeriksa (jika perlu ditampilkan)
                ->leftJoin('user as dokter', 'rm.dokter_pemeriksa', '=', 'dokter.iduser')
                ->select(
                    'rm.idrekam_medis',
                    'rm.created_at',
                    'rm.diagnosa',
                    'p.nama as nama_pet',
                    'pem_user.nama as nama_pemilik',
                    'dokter.nama as nama_dokter', // Nama dokter yang mencatat
                    'td.no_urut'
                )
                ->orderBy('rm.created_at', 'desc');

            // Filter: Default Hari Ini
            if ($request->input('filter') !== 'all') {
                $query->whereDate('rm.created_at', now());
            }

            $data = $query->paginate(10);

            // Variabel $data di sini berisi data Rekam Medis (History)
            return view('dashboard.dokter.rekam-medis.index', compact('data'));
            
        } catch (\Throwable $e) {
            Log::error('Gagal load rekam medis (Dokter): ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data.');
        }
    }

    // ✅ SHOW: Dokter melihat detail pemeriksaan & MENGELOLA TINDAKAN (CRUD DETAIL)
    public function show($id)
    {
        try {
            // 1. Ambil Header (Termasuk Kontak & Dokter Pemeriksa)
            $rekamMedis = DB::table('rekam_medis as rm')
                ->leftJoin('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->leftJoin('pet as p', 'td.idpet', '=', 'p.idpet')
                ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
                ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
                ->leftJoin('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->leftJoin('user as pem_user', 'pem.iduser', '=', 'pem_user.iduser')
                ->leftJoin('user as dokter', 'rm.dokter_pemeriksa', '=', 'dokter.iduser')
                ->select(
                    'rm.*',
                    'p.nama as nama_pet',
                    'jh.nama_jenis_hewan as jenis_hewan',
                    'rh.nama_ras as ras',
                    'pem_user.nama as nama_pemilik',
                    'pem.no_wa', // Kontak Pemilik
                    'dokter.nama as nama_dokter', // Nama Dokter Pemeriksa
                    'td.no_urut'
                )
                ->where('rm.idrekam_medis', $id)
                ->first();

            if (!$rekamMedis) abort(404);

            // 2. Ambil List Tindakan yang sudah diinput (Untuk ditampilkan di tabel detail)
            $detailTindakan = DB::table('detail_rekam_medis as drm')
                ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
                ->where('drm.idrekam_medis', $id)
                ->select('drm.*', 'ktt.kode', 'ktt.deskripsi_tindakan_terapi')
                ->get();

            // 3. Ambil Master Tindakan (Untuk Dropdown Tambah Tindakan)
            $listMasterTindakan = DB::table('kode_tindakan_terapi')
                ->select('idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi')
                ->orderBy('kode')
                ->get();

            return view('dashboard.dokter.rekam-medis.show', compact('rekamMedis', 'detailTindakan', 'listMasterTindakan'));
            
        } catch (\Throwable $e) {
            Log::error('Gagal load detail rekam medis (Dokter): ' . $e->getMessage());
            return back()->with('danger', 'Terjadi kesalahan saat memuat detail.');
        }
    }

    // ✅ UPDATE DIAGNOSA (Dokter hanya update Diagnosa, bukan data dasar)
    public function update(Request $request, $id)
    {
        $request->validate(['diagnosa' => 'required|string']);

        DB::table('rekam_medis')->where('idrekam_medis', $id)->update([
            'diagnosa' => $request->diagnosa
        ]);

        return back()->with('success', 'Diagnosa berhasil diperbarui.');
    }

    // =======================================================================
    // 🔥 CRUD DETAIL TINDAKAN (FITUR UTAMA DOKTER)
    // =======================================================================

    // 1. TAMBAH Item Tindakan
    public function tambahTindakan(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|integer',
            'detail' => 'nullable|string'
        ]);

        DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $id,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail
        ]);

        return back()->with('success', 'Tindakan berhasil ditambahkan.');
    }

    // 2. UPDATE Item Tindakan
    public function updateTindakan(Request $request, $iddetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|integer',
            'detail' => 'nullable|string'
        ]);
        
        DB::table('detail_rekam_medis')->where('iddetail_rekam_medis', $iddetail)->update([
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail
        ]);

        return back()->with('success', 'Tindakan berhasil diubah.');
    }

    // 3. HAPUS Item Tindakan
    public function hapusTindakan($iddetail)
    {
        DB::table('detail_rekam_medis')->where('iddetail_rekam_medis', $iddetail)->delete();
        return back()->with('success', 'Tindakan dihapus.');
    }
}