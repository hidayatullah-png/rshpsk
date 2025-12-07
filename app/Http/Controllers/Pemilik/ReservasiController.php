<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    // ==========================
    // INDEX (Daftar Reservasi)
    // ==========================
    public function index()
    {
        // 1. Ambil data pemilik berdasarkan iduser yang login
        $pemilik = DB::table('pemilik')
            ->where('iduser', Auth::user()->iduser)
            ->first();

        // Jika user login bukan pemilik / data belum lengkap
        if (!$pemilik) {
            return view('dashboard.pemilik.reservasi.index', ['reservasi' => collect()]);
        }

        // 2. Ambil data reservasi (temu_dokter)
        $reservasi = DB::table('temu_dokter as td')
            ->join('pet as p', 'p.idpet', '=', 'td.idpet')
            // Join ke tabel user via role_user untuk dapat nama dokter
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u', 'u.iduser', '=', 'ru.iduser')
            ->where('p.idpemilik', $pemilik->idpemilik)
            ->select(
                'td.*', // Ini akan mengambil idreservasi_dokter, no_urut, waktu_daftar, status
                'p.nama as nama_pet',
                'u.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->paginate(10); // Gunakan pagination agar rapi

        return view('dashboard.pemilik.reservasi.index', compact('reservasi'));
    }

    // ==========================
    // CREATE (Form Tambah Reservasi)
    // ==========================
    public function create()
    {
        $pemilik = DB::table('pemilik')
            ->where('iduser', Auth::user()->iduser)
            ->first();

        if (!$pemilik) {
            return redirect()->route('dashboard.pemilik.reservasi.index')
                ->with('error', 'Data pemilik tidak ditemukan. Hubungi admin.');
        }

        // Ambil daftar hewan milik pemilik
        $pets = DB::table('pet')
            ->where('idpemilik', $pemilik->idpemilik)
            ->select('idpet', 'nama', 'idras_hewan')
            ->get();
            
        // (Opsional) Ambil detail jenis hewan untuk tampilan di dropdown
        foreach($pets as $pet) {
            $ras = DB::table('ras_hewan')
                ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
                ->where('idras_hewan', $pet->idras_hewan)
                ->first();
            $pet->jenis_hewan = $ras ? $ras->nama_jenis_hewan : 'Hewan';
        }

        // Ambil daftar Dokter aktif
        $dokter = DB::table('user')
            ->join('role_user as ru', 'ru.iduser', '=', 'user.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->where('r.nama_role', 'dokter')
            ->where('ru.status', 1) 
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
        // 1. Validasi Input
        $request->validate([
            'idpet' => 'required|integer',
            'iddokter' => 'required|integer',
            'tanggal_periksa' => 'required|date|after_or_equal:today',
            // 'keluhan' => 'nullable' // Tidak divalidasi ketat karena tidak disimpan di DB
        ], [
            'idpet.required' => 'Hewan harus dipilih.',
            'iddokter.required' => 'Dokter harus dipilih.',
            'tanggal_periksa.after_or_equal' => 'Tanggal tidak boleh lewat.',
        ]);

        // 2. Cari idrole_user milik Dokter yang dipilih
        $idroleUserDokter = DB::table('role_user')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->where('role.nama_role', 'dokter')
            ->where('role_user.iduser', $request->iddokter)
            ->where('role_user.status', 1)
            ->value('role_user.idrole_user');

        if (!$idroleUserDokter) {
            return back()->with('error', 'Dokter tidak valid atau sedang tidak aktif.');
        }

        // 3. Generate Nomor Urut (Auto Increment per Hari per Dokter)
        // Logika: Cari no_urut terakhir untuk dokter tersebut pada tanggal yang dipilih
        $tanggalPilih = Carbon::parse($request->tanggal_periksa)->format('Y-m-d');
        
        $maxNoUrut = DB::table('temu_dokter')
            ->where('idrole_user', $idroleUserDokter)
            ->whereDate('waktu_daftar', $tanggalPilih)
            ->max('no_urut');
        
        $nextNoUrut = $maxNoUrut ? ($maxNoUrut + 1) : 1;

        // 4. Insert ke Database (Sesuai Struktur Tabel `temu_dokter`)
        DB::table('temu_dokter')->insert([
            'idpet' => $request->idpet,
            'idrole_user' => $idroleUserDokter,
            'no_urut' => $nextNoUrut,
            'waktu_daftar' => $request->tanggal_periksa . ' ' . now()->format('H:i:s'), // Gabungkan tanggal pilih dengan jam sekarang
            'status' => 'Menunggu', // Default status
        ]);

        return redirect()->route('dashboard.pemilik.reservasi.index')
            ->with('success', 'Berhasil mendaftar! Nomor antrian Anda: ' . $nextNoUrut);
    }

    // ==========================
    // DESTROY (Batalkan Reservasi)
    // ==========================
    public function destroy($id)
    {
        // Gunakan Primary Key yang benar: idreservasi_dokter
        $deleted = DB::table('temu_dokter')
            ->where('idreservasi_dokter', $id)
            ->where('status', 'Menunggu') // Hanya bisa hapus jika masih menunggu
            ->delete();

        if ($deleted) {
            return redirect()->route('dashboard.pemilik.reservasi.index')
                ->with('success', 'Reservasi berhasil dibatalkan.');
        }

        return back()->with('error', 'Gagal membatalkan. Status mungkin sudah diproses.');
    }
}