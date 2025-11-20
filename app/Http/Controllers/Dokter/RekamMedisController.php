<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    // ==========================
    // INDEX (lihat daftar rekam medis)
    // ==========================
    public function index(Request $request)
    {
        $selectedDate = $request->query('date', date('Y-m-d'));

        $list = DB::table('rekam_medis as rm')
            ->join('temu_dokter as td', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'td.idreservasi_dokter',
                'td.no_urut',
                'p.nama as nama_pet',
                'pem.nama as nama_pemilik',
                DB::raw('(SELECT COUNT(*) FROM detail_rekam_medis drm WHERE drm.idrekam_medis = rm.idrekam_medis) as jml_tindakan')
            )
            ->whereDate('rm.created_at', $selectedDate)
            ->orderBy('td.no_urut', 'asc')
            ->orderBy('rm.created_at', 'asc')
            ->get();

        return view('dashboard.dokter.rekam-medis.index', compact('list', 'selectedDate'));
    }

    // ==========================
    // CREATE (form input rekam medis baru)
    // ==========================
    public function create()
    {
        $pets = DB::table('pet as p')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->select('p.idpet', 'p.nama as nama_pet', 'pem.nama as nama_pemilik')
            ->orderBy('pem.nama')
            ->get();

        return view('dashboard.dokter.rekam-medis.create', compact('pets'));
    }

    // ==========================
    // STORE (proses simpan ke database)
    // ==========================
    public function store(Request $request)
    {
        $this->validateRekamMedis($request);
        $this->createRekamMedis($request);

        return redirect()->route('dashboard.dokter.rekam-medis.index')
            ->with('ok', 'Rekam medis baru berhasil ditambahkan!');
    }

    // ==========================
    // SHOW (detail rekam medis)
    // ==========================
    public function show($id)
    {
        $rekam = DB::table('rekam_medis as rm')
            ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->select('rm.*', 'p.nama as nama_pet', 'pem.nama as nama_pemilik')
            ->where('rm.idrekam_medis', $id)
            ->first();

        if (!$rekam) {
            abort(404, 'Rekam medis tidak ditemukan.');
        }

        $detailTindakan = DB::table('detail_rekam_medis as drm')
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->join('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->join('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->select(
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama as nama_kategori',
                'kk.nama_kategori_klinis',
                'drm.detail'
            )
            ->where('drm.idrekam_medis', $id)
            ->get();

        return view('dashboard.dokter.rekam-medis.show', compact('rekam', 'detailTindakan'));
    }

    // ==========================
    // EDIT (form ubah data)
    // ==========================
    public function edit($id)
    {
        $rekam = DB::table('rekam_medis')->where('idrekam_medis', $id)->first();
        if (!$rekam) {
            abort(404, 'Data tidak ditemukan.');
        }

        $pets = DB::table('pet as p')
            ->join('pemilik as pem', 'pem.idpemilik', '=', 'p.idpemilik')
            ->select('p.idpet', 'p.nama as nama_pet', 'pem.nama as nama_pemilik')
            ->get();

        return view('dashboard.dokter.rekam-medis.edit', compact('rekam', 'pets'));
    }

    // ==========================
    // UPDATE (proses ubah data)
    // ==========================
    public function update(Request $request, $id)
    {
        $this->validateRekamMedis($request);

        DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->update([
                'idpet' => $request->idpet,
                'anamnesa' => $this->formatText($request->anamnesa),
                'temuan_klinis' => $this->formatText($request->temuan_klinis),
                'diagnosa' => $this->formatText($request->diagnosa),
                'updated_at' => now(),
            ]);

        return redirect()->route('dokter.rekam-medis.index')
            ->with('ok', 'Data rekam medis berhasil diperbarui!');
    }

    // ==========================
    // VALIDATION
    // ==========================
    private function validateRekamMedis($request)
    {
        $request->validate([
            'idpet' => 'required|integer',
            'anamnesa' => 'required|string|max:255',
            'temuan_klinis' => 'nullable|string|max:255',
            'diagnosa' => 'required|string|max:255',
        ], [
            'idpet.required' => 'Pilih hewan terlebih dahulu.',
            'anamnesa.required' => 'Anamnesa wajib diisi.',
            'diagnosa.required' => 'Diagnosa wajib diisi.',
        ]);
    }

    // ==========================
    // HELPER - Insert ke database
    // ==========================
    private function createRekamMedis($request)
    {
        DB::table('rekam_medis')->insert([
            'idpet' => $request->idpet,
            'idreservasi_dokter' => null,
            'anamnesa' => $this->formatText($request->anamnesa),
            'temuan_klinis' => $this->formatText($request->temuan_klinis),
            'diagnosa' => $this->formatText($request->diagnosa),
            'created_at' => now(),
        ]);
    }

    // ==========================
    // HELPER - Format teks
    // ==========================
    private function formatText($text)
    {
        if (!$text) return null;
        return ucfirst(strtolower(trim($text)));
    }
}