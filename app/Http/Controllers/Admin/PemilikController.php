<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik; 
use App\Models\User;    
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PemilikController extends Controller
{
    /** Helper flash message */
    private function redirectWithMessage($route, $message, $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }

    /** Tampilkan semua data pemilik */
    public function index()
    {
        try {
            // Gunakan Eloquent dan Eager Loading 'user'
            $pemilikList = Pemilik::with('user')
                ->whereHas('user') // Hanya tampilkan pemilik yang punya user
                ->get()
                ->sortBy(function($pemilik) { // Urutkan berdasarkan nama user
                    return $pemilik->user->nama;
                });
                
            return view('dashboard.admin.pemilik.index', compact('pemilikList'));
        } catch (\Throwable $e) {
            Log::error("Error fetch pemilik: " . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data pemilik.');
        }
    }

    /** Form tambah pemilik */
    public function create()
    {
        // Ambil user yang BELUM punya profil pemilik
        $users = User::doesntHave('pemilik')->orderBy('nama')->get();
        return view('dashboard.admin.pemilik.create', compact('users'));
    }

    /** Simpan data baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validasi iduser, bukan membuat user baru
            'iduser' => 'required|exists:user,iduser|unique:pemilik,iduser',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ], [
            'iduser.required' => 'User wajib dipilih.',
            'iduser.unique' => 'User ini sudah memiliki profil pemilik.',
        ]);

        try {
            // Cukup buat data Pemilik, karena User sudah ada
            Pemilik::create($validated);

            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ Profil Pemilik baru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error("Error insert pemilik: " . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menambahkan data.');
        }
    }

    /** Form edit */
    public function edit($id)
    {
        try {
            $pemilik = Pemilik::with('user')->find($id);
            if (!$pemilik) {
                return $this->redirectWithMessage('dashboard.admin.pemilik.index', 'Data tidak ditemukan.', 'danger');
            }
            
            // Ambil semua user. User yang sedang diedit akan otomatis terpilih di form.
            $users = User::orderBy('nama')->get();
            
            return view('dashboard.admin.pemilik.edit', compact('pemilik', 'users'));
        } catch (\Throwable $e) {
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', 'Terjadi kesalahan memuat data.', 'danger');
        }
    }

    /** Update data */
    public function update(Request $request, $id)
    {
         $pemilik = Pemilik::findOrFail($id);

        $validated = $request->validate([
            // User (nama, email) di-update di UserController
            // Di sini hanya update data profil pemilik
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            // Validasi 'iduser' unik, tapi abaikan 'idpemilik' saat ini
            'iduser' => [
                'required',
                'exists:user,iduser',
                Rule::unique('pemilik')->ignore($id, 'idpemilik')
            ],
        ]);

        try {
            $pemilik->update($validated);
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ Data pemilik berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error("Error update pemilik: " . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal memperbarui data.');
        }
    }

    /** Hapus data */
    public function destroy($id)
    {
        try {
            // Gunakan Eloquent untuk mengecek relasi
            $pemilik = Pemilik::withCount('pets')->findOrFail($id);
            
            if ($pemilik->pets_count > 0) {
                return back()->with('danger', "Tidak bisa dihapus. Masih dipakai di $pemilik->pets_count data pet.");
            }

            // Hapus HANYA profil pemilik. 
            // User-nya dihapus terpisah melalui UserController.
            $pemilik->delete();
            return back()->with('success', '🗑️ Data profil pemilik berhasil dihapus.');
            
        } catch (\Throwable $e) {
            Log::error("Error delete pemilik: " . $e->getMessage());
            return back()->with('danger', 'Gagal menghapus data.');
        }
    }
}