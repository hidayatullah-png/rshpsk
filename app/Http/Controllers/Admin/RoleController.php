<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * 🔹 Helper: Validasi input role
     */
    protected function validateRole(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:role,nama_role,' . $id . ',idrole'
            : 'unique:role,nama_role';

        return $request->validate([
            'nama_role' => ['required', 'string', 'max:100', 'min:3', $uniqueRule],
        ], [
            'nama_role.required' => 'Nama role wajib diisi.',
            'nama_role.string' => 'Nama role harus berupa teks.',
            'nama_role.max' => 'Nama role maksimal 100 karakter.',
            'nama_role.min' => 'Nama role minimal 3 karakter.',
            'nama_role.unique' => 'Nama role sudah terdaftar.',
        ]);
    }

    /**
     * 🔹 Helper: Format nama role
     */
    private function formatNamaRole($nama)
    {
        return ucwords(strtolower(trim($nama)));
    }

    /**
     * 🔸 Tampilkan semua role
     */
    public function index()
    {
        $role = Role::orderBy('idrole', 'asc')->get();
        return view('dashboard.admin.role.index', compact('role'));
    }

    /**
     * 🔸 Form tambah role
     */
    public function create()
    {
        return view('dashboard.admin.role.create');
    }

    /**
     * 🔸 Simpan role baru ke database
     */
    public function store(Request $request)
    {
        $data = $this->validateRole($request);

        $data['nama_role'] = $this->formatNamaRole($data['nama_role']);

        Role::create($data);

        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '✅ Role baru berhasil ditambahkan.');
    }

    /**
     * 🔸 Form edit role
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('dashboard.admin.role.edit', compact('role'));
    }

    /**
     * 🔸 Update role
     */
    public function update(Request $request, $id)
    {
        $data = $this->validateRole($request, $id);

        $data['nama_role'] = $this->formatNamaRole($data['nama_role']);

        Role::where('idrole', $id)->update($data);

        // PERBAIKAN: Mengganti 'admin.role.index' menjadi 'dashboard.admin.role.index' agar konsisten
        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '✏️ Role berhasil diperbarui.');
    }

    /**
     * 🔸 Hapus role
     */
    public function destroy($id)
    {
        // Cek apakah role sedang digunakan oleh user
        $used = DB::table('role_user')->where('idrole', $id)->exists();

        if ($used) {
            // PERBAIKAN: Mengganti 'admin.role.index' menjadi 'dashboard.admin.role.index' agar konsisten
            return redirect()->route('dashboard.admin.role.index')
                ->with('danger', '⚠️ Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        Role::where('idrole', $id)->delete();

        // PERBAIKAN: Mengganti 'admin.role.index' menjadi 'dashboard.admin.role.index' agar konsisten
        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '🗑️ Role berhasil dihapus.');
    }
}