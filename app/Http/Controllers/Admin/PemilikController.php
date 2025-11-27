<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\Role;

class PemilikController extends Controller
{
    private function redirectWithMessage($route, $message, $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }

    public function index()
    {
        try {
            $pemilikList = Pemilik::with('user')
                ->whereHas('user')
                ->get()
                ->sortBy(function ($pemilik) {
                    return $pemilik->user->nama;
                });

            return view('dashboard.admin.pemilik.index', compact('pemilikList'));
        } catch (\Throwable $e) {
            Log::error("Error fetch pemilik: " . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data pemilik.');
        }
    }

    public function create()
    {
        return view('dashboard.admin.pemilik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Data Akun
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            // Data Profil
            'no_wa'    => 'required|string|max:20',
            'alamat'   => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1. Buat User (Nama & Email disimpan di sini)
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Buat Profil Pemilik
            // HAPUS 'nama' & 'email' dari sini agar tidak error
            Pemilik::create([
                'iduser' => $user->iduser,
                'no_wa'  => $request->no_wa,
                'alamat' => $request->alamat,
            ]);

            // 3. Assign Role
            $rolePemilik = Role::where('nama_role', 'Pemilik')->first();
            if ($rolePemilik) {
                $user->roles()->attach($rolePemilik->idrole, ['status' => 1]);
            }

            DB::commit();
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ User & Profil Pemilik berhasil dibuat.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error create pemilik: " . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $pemilik = Pemilik::with('user')->find($id);
            if (!$pemilik) return back()->with('danger', 'Data tidak ditemukan.');
            
            return view('dashboard.admin.pemilik.edit', compact('pemilik'));
        } catch (\Throwable $e) {
            return back()->with('danger', 'Error memuat data.');
        }
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::with('user')->findOrFail($id);
        $user = $pemilik->user;

        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => ['required', 'email', Rule::unique('user', 'email')->ignore($user->iduser, 'iduser')],
            'no_wa'  => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Update User (Nama & Email)
            $user->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

            // Update Pemilik (Hanya WA & Alamat)
            // HAPUS 'nama' & 'email' dari sini agar tidak error
            $pemilik->update([
                'no_wa'  => $request->no_wa,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ Data berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('danger', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pemilik = Pemilik::withCount('pets')->findOrFail($id);
            if ($pemilik->pets_count > 0) {
                return back()->with('danger', "Gagal! Pemilik ini masih memiliki hewan.");
            }
            $pemilik->delete();
            return back()->with('success', '🗑️ Profil pemilik berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }
}