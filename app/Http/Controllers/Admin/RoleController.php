<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleController extends Controller
{
    // ... (fungsi validateRole dan formatNamaRole tetap sama, tidak berubah) ...

    protected function validateRole(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:role,nama_role,' . $id . ',idrole'
            : 'unique:role,nama_role';

        return $request->validate([
            'nama_role' => ['required', 'string', 'max:100', 'min:3', $uniqueRule],
        ], [
            'nama_role.required' => 'Nama role wajib diisi.',
            'nama_role.unique'   => 'Nama role sudah terdaftar.',
        ]);
    }

    private function formatNamaRole($nama)
    {
        return ucwords(strtolower(trim($nama)));
    }

    /**
     * 🔸 Index (Menangani Data Aktif & Sampah)
     */
    public function index(Request $request)
    {
        $query = DB::table('role');

        // Cek mode trash
        if ($request->has('trash') && $request->trash == 1) {
            // Ambil data sampah (deleted_at TIDAK NULL)
            $query->whereNotNull('deleted_at');
        } else {
            // Ambil data aktif (deleted_at NULL)
            $query->whereNull('deleted_at');
        }

        $role = $query->orderBy('idrole', 'asc')->get();

        return view('dashboard.admin.role.index', compact('role'));
    }

    public function create()
    {
        return view('dashboard.admin.role.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRole($request);
        $insertData = ['nama_role' => $this->formatNamaRole($data['nama_role'])];
        
        DB::table('role')->insert($insertData);

        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '✅ Role baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = DB::table('role')->where('idrole', $id)->whereNull('deleted_at')->first();
        if (!$role) {
            return redirect()->route('dashboard.admin.role.index')->with('danger', 'Data tidak ditemukan.');
        }
        return view('dashboard.admin.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $exists = DB::table('role')->where('idrole', $id)->whereNull('deleted_at')->exists();
        if (!$exists) abort(404);

        $data = $this->validateRole($request, $id);
        $updateData = ['nama_role' => $this->formatNamaRole($data['nama_role'])];

        DB::table('role')->where('idrole', $id)->update($updateData);

        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '✏️ Role berhasil diperbarui.');
    }

    /**
     * 🔸 Soft Delete (Pindah ke Sampah)
     */
    public function destroy($id)
    {
        // Cek relasi sebelum hapus
        $used = DB::table('role_user')->where('idrole', $id)->exists();
        if ($used) {
            return back()->with('danger', '⚠️ Role sedang digunakan, tidak bisa dihapus.');
        }

        // Update timestamp deleted_at (Soft Delete)
        DB::table('role')->where('idrole', $id)->update(['deleted_at' => Carbon::now()]);

        return redirect()->route('dashboard.admin.role.index')
            ->with('success', '🗑️ Role dipindahkan ke sampah.');
    }

    /**
     * 🔸 Restore (Pulihkan Data)
     */
    public function restore($id)
    {
        // Update timestamp deleted_at jadi NULL
        DB::table('role')->where('idrole', $id)->update(['deleted_at' => null]);

        return redirect()->route('dashboard.admin.role.index', ['trash' => 1])
            ->with('success', '♻️ Role berhasil dipulihkan.');
    }
}