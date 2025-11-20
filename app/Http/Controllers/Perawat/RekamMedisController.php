<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    // ✅ INDEX: daftar reservasi tanpa RM + RM yang sudah ada (dengan filter tanggal)
    public function index(Request $request)
    {
        // Ambil tanggal dari query string, default = hari ini
        $selectedDate = $request->input('tanggal', date('Y-m-d'));

        // 🔹 List Reservasi tanpa Rekam Medis
        $reservasi = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->leftJoin('rekam_medis as rm', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'pem.nama as nama_pemilik'
            )
            ->whereNull('rm.idrekam_medis')
            ->whereDate('td.waktu_daftar', $selectedDate)
            ->orderBy('td.no_urut', 'asc')
            ->get();

        // 🔹 List Rekam Medis yang sudah ada pada tanggal itu
        $rekamMedis = DB::table('rekam_medis as rm')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'td.idreservasi_dokter',
                'p.nama as nama_pet',
                'pem.nama as nama_pemilik'
            )
            ->whereDate('rm.created_at', $selectedDate)
            ->orderBy('rm.created_at', 'desc')
            ->get();

        return view('dashboard.perawat.rekam-medis.index', compact('reservasi', 'rekamMedis', 'selectedDate'));
    }

    // ✅ CREATE (form buat RM baru)
    public function create(Request $request)
    {
        $idreservasi = $request->query('idreservasi');
        $idpet = $request->query('idpet');

        if (!$idreservasi || !$idpet) {
            abort(400, 'Parameter idreservasi & idpet wajib diisi.');
        }

        // 🔹 Ambil data reservasi & pet
        $ctx = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'pm.nama as nama_pemilik'
            )
            ->where('td.idreservasi_dokter', $idreservasi)
            ->where('p.idpet', $idpet)
            ->first();

        if (!$ctx) {
            abort(404, 'Reservasi atau Pet tidak ditemukan.');
        }

        // 🔹 Daftar Dokter aktif
        $listDokter = DB::table('user as u')
            ->join('role_user as ru', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->where('ru.status', 1)
            ->where('r.nama_role', 'Dokter')
            ->orderBy('u.nama', 'asc')
            ->select('u.iduser', 'u.nama')
            ->get();

        return view('dashboard.perawat.rekam-medis.create', compact('ctx', 'listDokter'));
    }

    // ✅ STORE (simpan RM baru)
    public function store(Request $request)
    {
        $request->validate([
            'idreservasi' => 'required|integer',
            'idpet' => 'required|integer',
            'iduser_dokter' => 'required|integer',
        ]);

        $data = [
            'idreservasi_dokter' => $request->idreservasi,
            'idpet' => $request->idpet,
            'dokter_pemeriksa' => $request->iduser_dokter,
            'anamnesa' => $request->anamnesa,
            'temuan_klinis' => $request->temuan_klinis,
            'diagnosa' => $request->diagnosa,
            'created_at' => now(),
        ];

        $id = DB::table('rekam_medis')->insertGetId($data);

        return redirect()->route('perawat.rekam-medis.show', $id)
            ->with('success', 'Rekam medis berhasil dibuat.');
    }

    // ✅ SHOW (Detail RM + CRUD tindakan)
    public function show($id)
    {
        $rekamMedis = DB::table('rekam_medis as rm')
            ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('user as u', 'u.iduser', '=', 'rm.dokter_pemeriksa')
            ->select('rm.*', 'p.nama as nama_pet', 'pem.nama as nama_pemilik', 'u.nama as nama_dokter')
            ->where('rm.idrekam_medis', $id)
            ->first();

        if (!$rekamMedis) abort(404, 'Rekam Medis tidak ditemukan.');

        $detailTindakan = DB::table('detail_rekam_medis as drm')
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->join('kategori as k', 'k.idkategori', '=', 'ktt.idkategori')
            ->join('kategori_klinis as kk', 'kk.idkategori_klinis', '=', 'ktt.idkategori_klinis')
            ->select(
                'drm.*',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama as nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->where('drm.idrekam_medis', $id)
            ->get();

        $listKode = DB::table('kode_tindakan_terapi')
            ->select('idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi')
            ->orderBy('kode', 'asc')
            ->get();

        return view('dashboard.perawat.rekam-medis.show', compact('rekamMedis', 'detailTindakan', 'listKode'));
    }

    // ✅ UPDATE HEADER RM
    public function update(Request $request, $id)
    {
        DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->update([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
            ]);

        return back()->with('success', 'Rekam medis berhasil diperbarui.');
    }

    // ✅ TAMBAH TINDAKAN
    public function tambahTindakan(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|integer',
        ]);

        DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $id,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);

        return back()->with('success', 'Tindakan berhasil ditambahkan.');
    }

    // ✅ UPDATE TINDAKAN
    public function updateTindakan(Request $request, $iddetail)
    {
        DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $iddetail)
            ->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

        return back()->with('success', 'Tindakan berhasil diperbarui.');
    }

    // ✅ HAPUS TINDAKAN
    public function hapusTindakan($iddetail)
    {
        DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $iddetail)
            ->delete();

        return back()->with('success', 'Tindakan berhasil dihapus.');
    }
}