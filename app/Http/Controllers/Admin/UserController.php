<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 🔹 Form tambah user baru
     */
    public function create()
    {
        return view('dashboard.admin.user.create');
    }

    /**
     * 🔹 Simpan user baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect ke index utama setelah membuat user baru
        return redirect()->route('dashboard.admin.role-user.index')
            ->with('success', '✅ User baru berhasil dibuat.');
    }

    /**
     * 🔹 Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.admin.user.edit', compact('user'));
    }

    /**
     * 🔹 Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            // Pastikan validasi unique mengabaikan user saat ini
            'email' => 'required|email|unique:user,email,' . $user->iduser . ',iduser', 
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('dashboard.admin.role-user.index')
            ->with('success', '✏️ Data user berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus user
     */
    public function destroy($id)
    {
        // PENTING: Sebaiknya tambahkan logika untuk menghapus
        // relasi role_user terlebih dahulu sebelum menghapus user
        // untuk menghindari foreign key constraint error.
        try {
            $user = User::findOrFail($id);
            
            // Hapus semua relasi di role_user
            $user->roleUsers()->delete(); 
            // Hapus user-nya
            $user->delete(); 

            return redirect()->route('dashboard.admin.role-user.index')
                ->with('success', '🗑️ User dan semua role terkait berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('danger', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}