<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    // ==========================
    // INDEX — Daftar Rekam Medis Pemilik
    // ==========================
public function index(Request $request)
{
    $pemilik = DB::table('pemilik')
        ->where('email', Auth::user()->email)
        ->first();

    // kalau pemilik belum terdaftar, kirim variabel kosong agar view tidak error
    if (!$pemilik) {
        return view('dashboard.pemilik.rekam-medis.index', [
            'rekamMedis' => collect(),
            'pets' => collect(), // 🟢 fix penting!
            'petId' => null,
            'tanggal' => null,
        ]);
    }

    // Filter opsional
    $petId = $request->query('idpet');
    $tanggal = $request->query('tanggal');

    $rekamMedis = DB::table('rekam_medis as rm')
        ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
        ->where('p.idpemilik', $pemilik->idpemilik)
        ->when($petId, fn($q) => $q->where('rm.idpet', $petId))
        ->when($tanggal, fn($q) => $q->whereDate('rm.created_at', $tanggal))
        ->select('rm.*', 'p.nama as nama_pet')
        ->orderByDesc('rm.created_at')
        ->get();

    $pets = DB::table('pet')
        ->where('idpemilik', $pemilik->idpemilik)
        ->select('idpet', 'nama')
        ->get();

    return view('dashboard.pemilik.rekam-medis.index', compact('rekamMedis', 'pets', 'petId', 'tanggal'));
}

    // ==========================
    // SHOW — Detail Rekam Medis
    // ==========================
    public function show($id)
    {
        // Header rekam medis (info umum)
        $header = DB::table('rekam_medis as rm')
            ->join('pet as p', 'p.idpet', '=', 'rm.idpet')
            ->join('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            ->select('rm.*', 'p.nama as nama_pet', 'pm.nama as nama_pemilik')
            ->where('rm.idrekam_medis', $id)
            ->first();

        if (!$header) {
            abort(404, 'Rekam medis tidak ditemukan.');
        }

        // Detail tindakan/terapi
        $detail = DB::table('detail_rekam_medis as d')
            ->join('kode_tindakan_terapi as k', 'k.idkode_tindakan_terapi', '=', 'd.idkode_tindakan_terapi')
            ->leftJoin('kategori as kt', 'kt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'kk.idkategori_klinis', '=', 'k.idkategori_klinis')
            ->where('d.idrekam_medis', $id)
            ->select(
                'd.*',
                'k.kode',
                'k.deskripsi_tindakan_terapi',
                'kt.nama_kategori as nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->get();

        return view('dashboard.pemilik.rekam-medis.show', compact('header', 'detail'));
    }

    // ==========================
    // CREATE — Form Tambah Keluhan Awal
    // ==========================
    public function create()
    {
        $pemilik = DB::table('pemilik')
            ->where('email', Auth::user()->email)
            ->first();

        if (!$pemilik) {
            abort(403, 'Akun pemilik tidak ditemukan.');
        }

        $pets = DB::table('pet')
            ->where('idpemilik', $pemilik->idpemilik)
            ->get();

        return view('dashboard.pemilik.rekam-medis.create', compact('pets'));
    }

    // ==========================
    // STORE — Simpan Keluhan Awal
    // ==========================
    public function store(Request $request)
    {
        $this->validateRekam($request);

        DB::table('keluhan_awal')->insert([
            'idpet'      => $request->idpet,
            'keluhan'    => $this->formatText($request->keluhan),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('pemilik.rekam-medis.index')
            ->with('ok', 'Keluhan awal berhasil dikirim. Dokter akan meninjau!');
    }

    // ==========================
    // VALIDATION
    // ==========================
    private function validateRekam(Request $request)
    {
        $request->validate([
            'idpet'   => 'required|integer',
            'keluhan' => 'required|string|min:5|max:255',
        ], [
            'idpet.required'   => 'Pilih hewan yang akan diperiksa.',
            'keluhan.required' => 'Tuliskan keluhan awal hewan Anda.',
        ]);
    }

    // ==========================
    // HELPER — Format Text
    // ==========================
    private function formatText(string $text)
    {
        return ucfirst(strtolower(trim($text)));
    }
}