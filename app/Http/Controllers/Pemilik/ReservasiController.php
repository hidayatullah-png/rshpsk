<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    // ==========================
    // INDEX (Daftar Reservasi)
    // ==========================
    public function index()
    {
        $pemilik = DB::table('pemilik')
            ->where('email', Auth::user()->email)
            ->first();

        if (!$pemilik) {
            return view('dashboard.pemilik.reservasi.index', ['reservasi' => collect()]);
        }

        $reservasi = DB::table('temu_dokter as td')
            ->join('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u', 'u.iduser', '=', 'ru.iduser')
            ->where('p.idpemilik', $pemilik->idpemilik)
            ->select(
                'td.*',
                'p.nama as nama_pet',
                'u.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();

        return view('dashboard.pemilik.reservasi.index', compact('reservasi'));
    }

    // ==========================
    // CREATE (Form Tambah Reservasi)
    // ==========================
public function create()
{
    $pemilik = DB::table('pemilik')
        ->where('email', Auth::user()->email)
        ->first();

    // 🧩 Tambahkan pengecekan agar tidak error saat pemilik belum terdaftar
    if (!$pemilik) {
        return redirect()->route('pemilik.reservasi.index')
            ->with('error', 'Akun Anda belum terdaftar sebagai pemilik. Silakan hubungi admin.');
    }

    $pets = DB::table('pet')
        ->where('idpemilik', $pemilik->idpemilik)
        ->select('idpet', 'nama')
        ->get();

    $dokter = DB::table('user')
        ->join('role_user as ru', 'ru.iduser', '=', 'user.iduser')
        ->join('role as r', 'r.idrole', '=', 'ru.idrole')
        ->where('r.nama_role', 'dokter')
        ->select('user.iduser', 'user.nama')
        ->orderBy('user.nama')
        ->get();

    return view('dashboard.pemilik.reservasi.create', compact('pets', 'dokter'));
}

    // ==========================
    // STORE (Simpan Reservasi)
    // ==========================
    public function store(Request $request)
    {
        $this->validateReservasi($request);

        // ambil idrole_user dokter
        $idroleUser = DB::table('role_user')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->where('role.nama_role', 'dokter')
            ->where('role_user.iduser', $request->iddokter)
            ->where('role_user.status', 1)
            ->value('role_user.idrole_user');

        // masukkan ke tabel temu_dokter
        DB::table('temu_dokter')->insert([
            'idpet' => $request->idpet,
            'idrole_user' => $idroleUser,
            'waktu_daftar' => now(),
            'status' => 0, // default: menunggu
            'keluhan' => $this->formatText($request->keluhan),
            'tanggal_periksa' => $request->tanggal_periksa,
            'created_at' => now(),
        ]);

        return redirect()->route('pemilik.reservasi.index')
            ->with('ok', 'Reservasi berhasil dibuat! Silakan menunggu konfirmasi dokter.');
    }

    // ==========================
    // VALIDATION
    // ==========================
    private function validateReservasi($request)
    {
        $request->validate([
            'idpet' => 'required|integer',
            'iddokter' => 'required|integer',
            'tanggal_periksa' => 'required|date|after_or_equal:today',
            'keluhan' => 'nullable|string|max:255',
        ], [
            'idpet.required' => 'Pilih hewan peliharaan.',
            'iddokter.required' => 'Pilih dokter yang ingin dituju.',
            'tanggal_periksa.after_or_equal' => 'Tanggal pemeriksaan tidak boleh sebelum hari ini.',
        ]);
    }

    // ==========================
    // HELPER (Format Text)
    // ==========================
    private function formatText($text)
    {
        return ucfirst(strtolower(trim($text)));
    }
}