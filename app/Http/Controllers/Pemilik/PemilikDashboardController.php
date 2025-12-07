<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get pemilik ID first
        $pemilik = DB::table('pemilik')->where('iduser', $user->iduser)->first();
        
        if (!$pemilik) {
            return redirect()->route('dashboard.pemilik.daftar-pet.create')
                ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $jumlahPet = DB::table('pet')
            ->where('idpemilik', $pemilik->idpemilik)
            ->count();

        $jumlahReservasi = DB::table('temu_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->where('temu_dokter.status', '!=', 'Selesai')
            ->count();

        return view('dashboard.pemilik.dashboard-pemilik', compact('jumlahPet', 'jumlahReservasi'));
    }
}