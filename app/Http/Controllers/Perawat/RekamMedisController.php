<?php

namespace App\Http\Controllers\Perawat;

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

    // ✅ INDEX
    public function index(Request $request)
    {
        try {
            // 1. AMBIL DATA ANTRIAN HARI INI
            $antrian = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->join('user as u_pem', 'pem.iduser', '=', 'u_pem.iduser')
                ->leftJoin('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                ->leftJoin('user as dokter', 'ru.iduser', '=', 'dokter.iduser')
                ->select(
                    'td.idreservasi_dokter',
                    'td.no_urut',
                    'td.status',
                    'td.waktu_daftar',
                    'p.nama as nama_pet',
                    'u_pem.nama as nama_pemilik',
                    'dokter.nama as nama_dokter'
                )
                ->whereDate('td.waktu_daftar', now()) 
                ->whereIn('td.status', ['In-line', 'Pending', 'Proses']) 
                ->orderBy('td.no_urut', 'asc')
                ->get();

            // 2. AMBIL DATA HISTORY
            $query = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->join('user as pem_user', 'pem.iduser', '=', 'pem_user.iduser')
                ->leftJoin('user as dokter', 'rm.dokter_pemeriksa', '=', 'dokter.iduser')
                ->select(
                    'rm.*',
                    'p.nama as nama_pet',
                    'pem_user.nama as nama_pemilik',
                    'dokter.nama as nama_dokter',
                    'td.waktu_daftar'
                )
                ->orderBy('rm.created_at', 'desc');

            if ($request->input('filter') !== 'all') {
                $query->whereDate('rm.created_at', now());
            }

            $history = $query->paginate(10); 

            return view('dashboard.perawat.rekam-medis.index', compact('antrian', 'history'));

        } catch (\Throwable $e) {
            Log::error('Gagal load rekam medis (Perawat): ' . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data.');
        }
    }

    // ✅ PANGGIL PASIEN
    public function panggil($idreservasi)
    {
        try {
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $idreservasi)
                ->update(['status' => 'Proses']);

            return back()->with('success', 'Pasien berhasil dipanggil.');
        } catch (\Throwable $e) {
            return back()->with('danger', 'Gagal memanggil pasien.');
        }
    }

    // 🔥 BARU: BATALKAN ANTRIAN
    public function batal($idreservasi)
    {
        try {
            // Ubah status jadi 'Batal' agar hilang dari list antrian (Index hanya menampilkan In-line/Pending/Proses)
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $idreservasi)
                ->update(['status' => 'Batal']);

            return back()->with('success', 'Antrian berhasil dibatalkan/dihapus.');
        } catch (\Throwable $e) {
            return back()->with('danger', 'Gagal membatalkan antrian.');
        }
    }

    // ✅ SHOW
    public function show($id)
    {
        try {
            $rekamMedis = DB::table('rekam_medis as rm')
                ->leftJoin('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->leftJoin('pet as p', 'td.idpet', '=', 'p.idpet')
                ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
                ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
                ->leftJoin('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->leftJoin('user as pem_user', 'pem.iduser', '=', 'pem_user.iduser')
                ->leftJoin('user as dokter', 'rm.dokter_pemeriksa', '=', 'dokter.iduser')
                ->select(
                    'rm.idrekam_medis', 'rm.created_at', 'rm.anamnesa', 'rm.temuan_klinis', 'rm.diagnosa',
                    'p.nama as nama_pet', 'jh.nama_jenis_hewan as jenis_hewan', 'rh.nama_ras as ras',
                    'pem_user.nama as nama_pemilik', 'pem.no_wa', 'dokter.nama as nama_dokter', 'td.no_urut'
                )
                ->where('rm.idrekam_medis', $id)
                ->first();

            if (!$rekamMedis) return back()->with('danger', 'Data tidak ditemukan.');

            $detailRekamMedis = DB::table('detail_rekam_medis as drm')
                ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
                ->where('drm.idrekam_medis', $id)
                ->select('drm.*', 'ktt.kode', 'ktt.deskripsi_tindakan_terapi')
                ->get();

            return view('dashboard.perawat.rekam-medis.show', compact('rekamMedis', 'detailRekamMedis'));

        } catch (\Throwable $e) {
            Log::error('Error Show RM: ' . $e->getMessage());
            return back()->with('danger', 'Terjadi kesalahan.');
        }
    }

    // ✅ CREATE FORM
    public function create(Request $request)
    {
        $selectedReservasi = null;
        if ($request->has('id_reservasi')) {
            $selectedReservasi = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
                ->join('user as u', 'pem.iduser', '=', 'u.iduser')
                ->leftJoin('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                ->leftJoin('user as dokter', 'ru.iduser', '=', 'dokter.iduser')
                ->where('td.idreservasi_dokter', $request->id_reservasi)
                ->select('td.*', 'p.nama as nama_pet', 'u.nama as nama_pemilik', 'dokter.iduser as id_user_dokter')
                ->first();
        }

        $reservasiList = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u_pem', 'pem.iduser', '=', 'u_pem.iduser')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->where(function($q) use ($selectedReservasi) {
                $q->whereIn('td.status', ['In-line', 'Pending']);
                if ($selectedReservasi) {
                    $q->orWhere('td.idreservasi_dokter', $selectedReservasi->idreservasi_dokter);
                }
            })
            ->whereNull('rm.idrekam_medis')
            ->select('td.idreservasi_dokter', 'td.no_urut', 'td.waktu_daftar', 'p.nama as nama_pet', 'u_pem.nama as nama_pemilik')
            ->orderBy('td.no_urut', 'asc')
            ->get();

        $dokter = DB::table('user as u')
            ->join('role_user as ru', 'u.iduser', '=', 'ru.iduser')
            ->where('ru.idrole', 2)->where('ru.status', 1)
            ->select('u.iduser', 'u.nama')
            ->get();

        $listTindakan = DB::table('kode_tindakan_terapi')
            ->select('idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi')
            ->orderBy('kode', 'asc')
            ->get();

        return view('dashboard.perawat.rekam-medis.create', compact('reservasiList', 'dokter', 'selectedReservasi', 'listTindakan'));
    }

    // ✅ STORE
    public function store(Request $r)
    {
        $r->validate([
            'idreservasi_dokter' => 'required|integer|unique:rekam_medis,idreservasi_dokter',
            'dokter_pemeriksa' => 'required|integer',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'nullable|array',
            'tindakan.*' => 'integer|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
        ]);

        DB::beginTransaction();
        try {
            $idRekamMedis = DB::table('rekam_medis')->insertGetId([
                'idreservasi_dokter' => $r->idreservasi_dokter,
                'dokter_pemeriksa' => $r->dokter_pemeriksa,
                'anamnesa' => $r->anamnesa,
                'temuan_klinis' => $r->temuan_klinis,
                'diagnosa' => $r->diagnosa,
                'created_at' => now(),
            ]);

            if ($r->has('tindakan')) {
                $detailData = [];
                foreach ($r->tindakan as $idTindakan) {
                    $detailData[] = [
                        'idrekam_medis' => $idRekamMedis,
                        'idkode_tindakan_terapi' => $idTindakan,
                        'detail' => null, 
                    ];
                }
                if (!empty($detailData)) {
                    DB::table('detail_rekam_medis')->insert($detailData);
                }
            }

            DB::table('temu_dokter')->where('idreservasi_dokter', $r->idreservasi_dokter)->update(['status' => 'Selesai']);
            DB::commit();
            return $this->redirectMsg('dashboard.perawat.rekam-medis.index', '✅ Rekam Medis berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Insert RekamMedis error: ' . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data.');
        }
    }

    // ✅ UPDATE HEADER RM
    public function update(Request $request, $id)
    {
        $request->validate(['diagnosa' => 'required|string|max:255']);
        try {
            DB::table('rekam_medis')->where('idrekam_medis', $id)->update([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
            ]);
            return back()->with('success', 'Rekam medis diperbarui.');
        } catch (\Throwable $e) {
            return back()->with('danger', 'Gagal update.');
        }
    }

    // ✅ ACTION LAINNYA
    public function tambahTindakan(Request $request, $id)
    {
        $request->validate(['idkode_tindakan_terapi' => 'required|integer']);
        DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $id,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);
        return back()->with('success', 'Tindakan ditambahkan.');
    }

    public function updateTindakan(Request $request, $iddetail)
    {
        DB::table('detail_rekam_medis')->where('iddetail_rekam_medis', $iddetail)->update([
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);
        return back()->with('success', 'Tindakan diperbarui.');
    }

    public function hapusTindakan($iddetail)
    {
        DB::table('detail_rekam_medis')->where('iddetail_rekam_medis', $iddetail)->delete();
        return back()->with('success', 'Tindakan dihapus.');
    }
}