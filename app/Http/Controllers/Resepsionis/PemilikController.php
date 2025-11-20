<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PemilikController extends Controller
{
    public function create()
    {
        return view('dashboard.resepsionis.registrasi-pemilik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 🧩 1. Insert ke tabel user
                $iduser = DB::table('user')->insertGetId([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // 🧩 2. Insert ke tabel pemilik
                DB::table('pemilik')->insert([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_wa' => $request->no_wa,
                    'alamat' => $request->alamat,
                    'iduser' => $iduser,
                ]);

                // 🧩 3. (Opsional) Tambah ke tabel role_user, kalau kamu mau langsung beri role “Pemilik”
                DB::table('role_user')->insert([
                    'iduser' => $iduser,
                    'idrole' => 4, // contoh ID role “Pemilik”
                    'status' => 'Aktif',
                ]);
            });

            return back()->with('success', '✅ Pemilik berhasil didaftarkan.');
        } catch (\Throwable $e) {
            Log::error("❌ Error tambah pemilik: " . $e->getMessage());
            return back()->with('danger', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
