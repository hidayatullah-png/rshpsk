<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemuDokterController extends Controller
{
    public function create(Request $request)
    {
        $selectedDate = $request->query('date', date('Y-m-d'));

        // 1. Ambil Antrian (Join ke User via Role_User untuk dapat nama dokter)
        $antrian = DB::table('temu_dokter as td')
            ->join('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u', 'u.iduser', '=', 'ru.iduser')
            ->select(
                'td.*', 
                'p.nama as nama_pet',
                'u.nama as nama_dokter'
            )
            ->whereDate('td.waktu_daftar', $selectedDate)
            ->orderBy('td.no_urut')
            ->get();

        // 2. Ambil List Hewan
        $allPets = DB::table('pet')->select('idpet', 'nama')->get();

        // 3. Ambil List Dokter (Filter Integer Status = 1)
        $doctors = DB::table('role_user as ru')
            ->join('user as u', 'u.iduser', '=', 'ru.iduser')
            ->join('role as r', 'r.idrole', '=', 'ru.idrole')
            ->where('r.nama_role', 'Dokter') 
            ->where('ru.status', 1) // <--- Integer (Hanya dokter aktif)
            ->select('ru.idrole_user', 'u.nama as nama_dokter')
            ->get();

        return view('dashboard.resepsionis.temu-dokter.create', compact('selectedDate', 'antrian', 'allPets', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpet' => 'required|integer',
            'idrole_user' => 'required|integer', 
        ]);

        $lastNo = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', now()->toDateString())
            ->max('no_urut') ?? 0;

        $noUrut = $lastNo + 1;

        DB::table('temu_dokter')->insert([
            'no_urut' => $noUrut,
            'waktu_daftar' => now(),
            'status' => 'In-line', // <--- String (Default char 7)
            'idpet' => $request->idpet,
            'idrole_user' => $request->idrole_user, 
        ]);

        return redirect()->back()->with('success', "Pendaftaran berhasil, No. Urut: {$noUrut}");
    }

    public function update(Request $request, $id)
    {
        // Validasi input hanya boleh string 'Selesai' atau 'Batal'
        $request->validate(['status' => 'required|in:Selesai,Batal']); 
        
        DB::table('temu_dokter')
            ->where('idreservasi_dokter', $id)
            ->update(['status' => $request->status]); // <--- Update String
        
        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('temu_dokter')->where('idreservasi_dokter', $id)->delete();
        return back()->with('success', 'Antrian berhasil dihapus.');
    }
}