<?php

// Ganti namespace agar sesuai dengan path file (Admin)
namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser; // Import RoleUser yang sangat penting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Ganti nama class agar sesuai dengan nama file (UserRoleController)
class UserRoleController extends Controller
{
    /**
     * 🔸 Tampilkan semua user dan role user
     */
    public function index()
    {
        // PERBAIKAN: Ganti 'roles' menjadi 'roleUsers.role' 
        // agar sesuai dengan apa yang dibutuhkan oleh index.blade.php
        $users = User::with(['roleUsers.role'])->orderBy('nama')->get();
        
        // $roles tidak digunakan di index.blade.php, tapi tidak apa-apa
        $roles = Role::orderBy('nama_role')->get(); 

        return view('dashboard.admin.role-user.index', compact('users', 'roles'));
    }

    /**
     * 🔸 Form tambah user baru
     */
    public function create()
    {
        // Ambil semua user yang belum memiliki role
        // 'roles' adalah nama relasi yang benar di User.php (belongsToMany)
        $users = User::doesntHave('roles')->orderBy('nama')->get();
        $roles = Role::orderBy('nama_role')->get();
        
        // Pastikan view ini ada
        return view('dashboard.admin.role-user.create', compact('users', 'roles'));
    }

    /**
     * 🔸 Simpan user baru dan role-nya
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            'idrole' => 'required|exists:role,idrole',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'idrole.required' => 'Role wajib dipilih.',
            'idrole.exists' => 'Role tidak valid.',
        ]);

        // Buat user baru
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tambahkan role ke user baru
        // 'roles' adalah relasi belongsToMany, 'attach' sudah benar
        $user->roles()->attach($request->idrole, ['status' => 1]); 

        return redirect()->route('dashboard.admin.role-user.index')
            ->with('success', '✅ User baru dan role berhasil ditambahkan.');
    }

    /**
     * 🔸 Form edit user + role
     */
    public function edit($id)
    {
        // $id di sini adalah 'idrole_user' dari blade
        $roleUser = RoleUser::with(['user', 'role'])->find($id);

        if (!$roleUser) {
            return redirect()->route('dashboard.admin.role-user.index')
                ->with('danger', '❌ Data tidak ditemukan.');
        }

        // Ambil semua role
        $roles = Role::orderBy('nama_role')->get();
        
        // Ambil semua user.
        $users = User::orderBy('nama')->get();

        return view('dashboard.admin.role-user.edit', compact('roleUser', 'roles', 'users'));
    }

    /**
     * 🔸 Form edit status role user
     * (Anda punya method ini, pastikan route-nya ada)
     */
    public function editStatus($id)
    {
        $roleUser = RoleUser::with(['user', 'role'])->findOrFail($id);

        return view('dashboard.admin.role-user.edit-status', compact('roleUser'));
    }

    /**
     * 🔸 Update data user dan role
     */
    public function update(Request $request, $id)
    {
        // $id di sini adalah 'idrole_user'
        $roleUser = RoleUser::findOrFail($id);

        $request->validate([
            'idrole' => 'required|exists:role,idrole',
            'status' => 'required|boolean',
        ], [
            'idrole.required' => 'Role wajib dipilih.',
            'idrole.exists' => 'Role tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.boolean' => 'Status tidak valid.',
        ]);

        // Cek duplikasi role untuk user yang sama, kecuali role_user yang sedang diupdate
        $exists = RoleUser::where('iduser', $roleUser->iduser)
            ->where('idrole', $request->idrole)
            ->where('idrole_user', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->route('dashboard.admin.role-user.index')
                ->with('danger', '⚠️ User ini sudah memiliki role tersebut.');
        }

        $roleUser->update([
            'idrole' => $request->idrole,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.admin.role-user.index')
            ->with('success', '✏️ Role user berhasil diperbarui.');
    }

    /**
     * 🔸 Update status role user
     * (Anda punya method ini, pastikan route-nya ada)
     */
    public function updateStatus(Request $request, $id)
    {
        $roleUser = RoleUser::findOrFail($id);

        $request->validate([
            'status' => 'required|boolean',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.boolean' => 'Status tidak valid.',
        ]);

        $roleUser->update([
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.admin.role-user.index')
            ->with('success', '✏️ Data user dan role berhasil diperbarui.');
    }

    /**
     * 🔸 Hapus user dan role-nya
     */
    public function destroy($id)
    {
        // Hanya menghapus relasi role_user, bukan user-nya. Ini sudah benar.
        RoleUser::destroy($id);

        return redirect()
            ->route('dashboard.admin.role-user.index')
            ->with('success', '🗑️ Role user berhasil dihapus.');
    }
}