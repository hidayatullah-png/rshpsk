<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// Import Model
use App\Models\User;
use App\Models\Pemilik;
use App\Models\Role;

class PemilikController extends Controller
{
    /**
     * Tampilkan Form Registrasi Pemilik
     */
    public function create()
    {
        return view('dashboard.resepsionis.registrasi-pemilik.create');
    }

    /**
     * Proses Simpan Data (User + Pemilik + Role)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            // Data Akun
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            // Data Profil
            'no_wa'    => 'required|string|max:20',
            'alamat'   => 'required|string|max:255',
        ], [
            'email.unique'   => 'Email sudah terdaftar.',
            'password.min'   => 'Password minimal 6 karakter.',
            'no_wa.required' => 'Nomor WA wajib diisi.',
        ]);

        DB::beginTransaction(); // Mulai Transaksi

        try {
            // A. Buat User Baru (Simpan Nama & Email di sini)
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // B. Buat Profil Pemilik
            // ⚠️ PENTING: Jangan simpan 'nama' & 'email' di sini agar tidak error "Column not found"
            Pemilik::create([
                'iduser' => $user->iduser,
                'no_wa'  => $request->no_wa,
                'alamat' => $request->alamat,
            ]);

            // C. Berikan Role "Pemilik"
            $rolePemilik = Role::where('nama_role', 'Pemilik')->first();

            if ($rolePemilik) {
                // Attach role dengan status 1 (Aktif)
                $user->roles()->attach($rolePemilik->idrole, ['status' => 1]);
            } else {
                Log::warning("Role 'Pemilik' tidak ditemukan saat resepsionis input data.");
            }

            DB::commit(); // Simpan permanen

            return redirect()->route('dashboard.resepsionis.registrasi-pemilik.create')
                ->with('success', '✅ Pemilik baru berhasil didaftarkan.');

        } catch (\Throwable $e) {
            DB::rollBack(); // Batalkan jika error
            Log::error("Error Resepsionis Create Pemilik: " . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('danger', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}