<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
// Import Model Dokter & Perawat
use App\Models\Dokter;
use App\Models\Perawat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Penting untuk Transaction

class UserRoleController extends Controller
{
    // ... (method index, create, store, edit tetap sama seperti sebelumnya) ...

    public function index(Request $request)
    {
        $targetStatus = $request->has('status') && $request->status == '0' ? 0 : 1;
        $isShowingNonActive = $targetStatus === 0;

        $users = User::with(['roleUsers.role'])->orderBy('nama')->get();
        $tableRows = collect();

        foreach ($users as $user) {
            foreach ($user->roleUsers ?? [] as $roleUser) {
                if ($roleUser->status == $targetStatus) {
                    $isPemilik = strtolower($roleUser->role->nama_role) === 'pemilik';
                    $tableRows->push((object)[
                        'user_name' => $user->nama,
                        'role_name' => $roleUser->role->nama_role,
                        'status'    => $roleUser->status,
                        'id'        => $roleUser->idrole_user,
                        'is_pemilik'=> $isPemilik,
                        'roleUser'  => $roleUser 
                    ]);
                }
            }
        }
        $sortedRows = $tableRows->sortBy('is_pemilik');
        return view('dashboard.admin.role-user.index', compact('sortedRows', 'isShowingNonActive'));
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        $roles = Role::orderBy('nama_role')->get();
        return view('dashboard.admin.role-user.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email', 
            'password' => 'required|min:6',
            'idrole'   => 'required|exists:role,idrole',
        ]);

        $role = Role::find($request->idrole);
        $roleName = strtolower($role->nama_role);
        $isDokter = str_contains($roleName, 'dokter');
        $isPerawat = str_contains($roleName, 'perawat');

        if ($isDokter) {
            $request->validate([
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string',
                'jenis_kelamin' => 'required|in:L,P',
                'bidang_dokter' => 'required|string',
            ]);
        } elseif ($isPerawat) {
            $request->validate([
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string',
                'jenis_kelamin' => 'required|in:L,P',
                'pendidikan'    => 'required|string',
            ]);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->attach($request->idrole, ['status' => 1]); 

            if ($isDokter) {
                Dokter::create([
                    'id_user'       => $user->getKey(),
                    'alamat'        => $request->alamat,
                    'no_hp'         => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'bidang_dokter' => $request->bidang_dokter,
                ]);
            } elseif ($isPerawat) {
                Perawat::create([
                    'id_user'       => $user->getKey(),
                    'alamat'        => $request->alamat,
                    'no_hp'         => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'pendidikan'    => $request->pendidikan,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.admin.role-user.index')
                ->with('success', '✅ Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', '❌ Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $roleUser = RoleUser::with(['user', 'role'])->find($id);
        if (!$roleUser) {
            return redirect()->route('dashboard.admin.role-user.index')->with('danger', '❌ Data tidak ditemukan.');
        }
        $roles = Role::orderBy('nama_role')->get();
        return view('dashboard.admin.role-user.edit', compact('roleUser', 'roles'));
    }

    /**
     * 🔸 Update data user + role + profil dokter/perawat
     */
    public function update(Request $request, $id)
    {
        // 1. Ambil Data
        $roleUser = RoleUser::with('user')->findOrFail($id);
        $user     = $roleUser->user;

        // 2. Validasi User & Role Dasar
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:user,email,' . $user->getKey() . ',' . $user->getKeyName(),
            'idrole' => 'required|exists:role,idrole',
            'status' => 'required', 
        ]);

        // 3. Cek Role Baru untuk Validasi Tambahan
        $role = Role::find($request->idrole);
        $roleName = strtolower($role->nama_role);
        $isDokter = str_contains($roleName, 'dokter');
        $isPerawat = str_contains($roleName, 'perawat');

        // Validasi field spesifik jika role berubah jadi Dokter/Perawat
        if ($isDokter) {
            $request->validate([
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string',
                'jenis_kelamin' => 'required|in:L,P',
                'bidang_dokter' => 'required|string',
            ]);
        } elseif ($isPerawat) {
            $request->validate([
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string',
                'jenis_kelamin' => 'required|in:L,P',
                'pendidikan'    => 'required|string',
            ]);
        }

        // 4. Cek Duplikasi Role (Agar user tidak punya 2 role yang sama)
        $exists = RoleUser::where('iduser', $user->getKey())
            ->where('idrole', $request->idrole)
            ->where('idrole_user', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('danger', '⚠️ User ini sudah memiliki role tersebut.')->withInput();
        }

        // 5. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // A. Update User (Nama & Email)
            $user->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

            // B. Update RoleUser (Role & Status)
            $roleUser->update([
                'idrole' => $request->idrole,
                'status' => $request->status,
            ]);

            // C. Update atau Buat Profil Dokter/Perawat
            // Fungsi updateOrCreate akan mencari data berdasarkan 'id_user'.
            // Jika ada -> Update. Jika tidak ada -> Create baru.
            if ($isDokter) {
                Dokter::updateOrCreate(
                    ['id_user' => $user->getKey()], // Kriteria pencarian
                    [
                        'alamat'        => $request->alamat,
                        'no_hp'         => $request->no_hp,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'bidang_dokter' => $request->bidang_dokter,
                    ]
                );
            } elseif ($isPerawat) {
                Perawat::updateOrCreate(
                    ['id_user' => $user->getKey()], // Kriteria pencarian
                    [
                        'alamat'        => $request->alamat,
                        'no_hp'         => $request->no_hp,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'pendidikan'    => $request->pendidikan,
                    ]
                );
            }

            DB::commit(); // Simpan permanen

            return redirect()->route('dashboard.admin.role-user.index')
                ->with('success', '✏️ Data user, role, dan profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan jika error
            return redirect()->back()
                ->with('danger', '❌ Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $roleUser = RoleUser::findOrFail($id);
        $roleUser->delete();
        return redirect()->route('dashboard.admin.role-user.index')->with('success', '🗑️ Role user berhasil dihapus.');
    }
}