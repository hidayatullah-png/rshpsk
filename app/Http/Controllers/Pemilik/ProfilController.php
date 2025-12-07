<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * Tampilkan profil pemilik yang sedang login
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data pemilik dengan pet count dalam satu query
        $profil = DB::table('pemilik as p')
            ->join('user as u', 'p.iduser', '=', 'u.iduser')
            ->leftJoin('pet as pet', 'p.idpemilik', '=', 'pet.idpemilik')
            ->select(
                'p.idpemilik',
                'u.nama as nama_user',
                'u.email',
                'p.no_wa as no_hp',
                'p.alamat',
                DB::raw('COUNT(pet.idpet) as jumlah_pet')
            )
            ->where('u.iduser', $user->iduser)
            ->groupBy('p.idpemilik', 'u.nama', 'u.email', 'p.no_wa', 'p.alamat')
            ->first();

        // Fallback jika data pemilik belum ada (misal baru register user saja)
        if (!$profil) {
            $profil = (object) [
                'idpemilik' => null,
                'nama_user' => $user->nama,
                'email' => $user->email,
                'no_hp' => '-',
                'alamat' => '-',
                'jumlah_pet' => 0,
            ];
        }

        return view('dashboard.pemilik.profil.index', compact('profil'));
    }
}