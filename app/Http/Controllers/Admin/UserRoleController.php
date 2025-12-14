<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserRoleController extends Controller
{
    // ... (Method index, create, store, edit, update TETAP SAMA) ...

    public function index(Request $request)
    {
        $isTrash = $request->has('trash') && $request->trash == '1';
        $targetStatus = $request->has('status') && $request->status == '0' ? 0 : 1;
        $viewMode = 'active'; 
        
        $query = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->select(
                'ru.idrole_user as id',
                'ru.status',
                'ru.idrole',
                'u.iduser',
                'u.nama as user_name',
                'u.deleted_at',
                'r.nama_role as role_name'
            );

        if ($isTrash) {
            $viewMode = 'trash';
            $query->whereNotNull('u.deleted_at');
        } else {
            $viewMode = $targetStatus === 1 ? 'active' : 'inactive';
            $query->whereNull('u.deleted_at');
            $query->where('ru.status', $targetStatus);
        }

        $rawData = $query->orderBy('u.nama')->get();

        $tableRows = $rawData->map(function($row) {
            return (object) [
                'id'         => $row->id,
                'id_user'    => $row->iduser,
                'user_name'  => $row->user_name,
                'role_name'  => $row->role_name,
                'status'     => $row->status,
                'is_pemilik' => strtolower($row->role_name) === 'pemilik',
                'deleted_at' => $row->deleted_at
            ];
        });

        $sortedRows = $tableRows->sortBy('is_pemilik');

        return view('dashboard.admin.role-user.index', [
            'sortedRows' => $sortedRows,
            'viewMode'   => $viewMode,
            'isShowingNonActive' => $isTrash || $targetStatus === 0 
        ]);
    }

    public function create()
    {
        $users = DB::table('user')->whereNull('deleted_at')->orderBy('nama')->get();
        $roles = DB::table('role')->whereNull('deleted_at')->orderBy('nama_role')->get();
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

        $role = DB::table('role')->where('idrole', $request->idrole)->first();
        $roleName = strtolower($role->nama_role);
        $isDokter = str_contains($roleName, 'dokter');
        $isPerawat = str_contains($roleName, 'perawat');

        if ($isDokter) {
            $request->validate(['alamat' => 'required', 'no_hp' => 'required', 'jenis_kelamin' => 'required', 'bidang_dokter' => 'required']);
        } elseif ($isPerawat) {
            $request->validate(['alamat' => 'required', 'no_hp' => 'required', 'jenis_kelamin' => 'required', 'pendidikan' => 'required']);
        }

        DB::beginTransaction();
        try {
            $idUser = DB::table('user')->insertGetId([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::table('role_user')->insert([
                'iduser' => $idUser,
                'idrole' => $request->idrole,
                'status' => 1,
            ]);

            if ($isDokter) {
                DB::table('dokter')->insert(['id_user' => $idUser, 'alamat' => $request->alamat, 'no_hp' => $request->no_hp, 'jenis_kelamin' => $request->jenis_kelamin, 'bidang_dokter' => $request->bidang_dokter]);
            } elseif ($isPerawat) {
                DB::table('perawat')->insert(['id_user' => $idUser, 'alamat' => $request->alamat, 'no_hp' => $request->no_hp, 'jenis_kelamin' => $request->jenis_kelamin, 'pendidikan' => $request->pendidikan]);
            }

            DB::commit();
            return redirect()->route('dashboard.admin.role-user.index')->with('success', '✅ Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', '❌ Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $roleUser = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole_user', $id)
            ->select('ru.*', 'u.nama', 'u.email') 
            ->first();
        
        if (!$roleUser) {
            return redirect()->route('dashboard.admin.role-user.index')->with('danger', '❌ Data tidak ditemukan.');
        }

        $roleUser->user = (object) ['nama' => $roleUser->nama, 'email' => $roleUser->email];
        $roles = DB::table('role')->whereNull('deleted_at')->orderBy('nama_role')->get();
        
        return view('dashboard.admin.role-user.edit', compact('roleUser', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $currentData = DB::table('role_user')->where('idrole_user', $id)->first();
        if (!$currentData) abort(404);

        $idUser = $currentData->iduser;

        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:user,email,' . $idUser . ',iduser',
            'idrole' => 'required|exists:role,idrole',
            'status' => 'required|in:0,1',
        ]);

        $exists = DB::table('role_user')
            ->where('iduser', $idUser)
            ->where('idrole', $request->idrole)
            ->where('idrole_user', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('danger', '⚠️ User ini sudah memiliki role tersebut.')->withInput();
        }

        $role = DB::table('role')->where('idrole', $request->idrole)->first();
        $roleName = strtolower($role->nama_role);
        $isDokter = str_contains($roleName, 'dokter');
        $isPerawat = str_contains($roleName, 'perawat');

        if ($isDokter) {
            $request->validate(['alamat' => 'required', 'no_hp' => 'required', 'jenis_kelamin' => 'required', 'bidang_dokter' => 'required']);
        } elseif ($isPerawat) {
            $request->validate(['alamat' => 'required', 'no_hp' => 'required', 'jenis_kelamin' => 'required', 'pendidikan' => 'required']);
        }

        DB::beginTransaction();
        try {
            DB::table('user')->where('iduser', $idUser)->update(['nama' => $request->nama, 'email' => $request->email]);
            DB::table('role_user')->where('idrole_user', $id)->update(['idrole' => $request->idrole, 'status' => $request->status]);

            if ($isDokter) {
                DB::table('dokter')->updateOrInsert(['id_user' => $idUser], ['alamat' => $request->alamat, 'no_hp' => $request->no_hp, 'jenis_kelamin' => $request->jenis_kelamin, 'bidang_dokter' => $request->bidang_dokter]);
            } elseif ($isPerawat) {
                DB::table('perawat')->updateOrInsert(['id_user' => $idUser], ['alamat' => $request->alamat, 'no_hp' => $request->no_hp, 'jenis_kelamin' => $request->jenis_kelamin, 'pendidikan' => $request->pendidikan]);
            }

            DB::commit();
            return redirect()->route('dashboard.admin.role-user.index')->with('success', '✏️ Data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * DESTROY: Hapus Data
     */
    public function destroy($id)
    {
        $roleUser = DB::table('role_user')->where('idrole_user', $id)->first();

        if ($roleUser) {
            $user = DB::table('user')->where('iduser', $roleUser->iduser)->first();
            
            if ($user) {
                if ($user->deleted_at !== null) {
                    // === FORCE DELETE (Hapus Permanen) ===
                    // Hapus SEMUA data terkait user ini
                    DB::table('dokter')->where('id_user', $user->iduser)->delete();
                    DB::table('perawat')->where('id_user', $user->iduser)->delete();
                    
                    // Hapus SEMUA role milik user ini (bukan cuma yg diklik)
                    DB::table('role_user')->where('iduser', $user->iduser)->delete();
                    
                    DB::table('user')->where('iduser', $user->iduser)->delete();

                    return redirect()->route('dashboard.admin.role-user.index', ['trash' => 1])
                        ->with('success', '🗑️ User telah dihapus secara PERMANEN.');
                } else {
                    // === SOFT DELETE (Isi timestamp deleted_at) ===
                    $now = Carbon::now();

                    // 1. Soft Delete User
                    DB::table('user')->where('iduser', $user->iduser)->update([
                        'deleted_at' => $now
                    ]);
                    
                    // 2. Soft Delete SEMUA Role milik user ini
                    // Update status jadi 0 dan isi deleted_at
                    DB::table('role_user')->where('iduser', $user->iduser)->update([
                        'status' => 0,
                        'deleted_at' => $now 
                    ]); 
                    
                    return redirect()->route('dashboard.admin.role-user.index')
                        ->with('success', 'User dipindahkan ke sampah (Soft Delete).');
                }
            }
        }
        return redirect()->route('dashboard.admin.role-user.index')->with('danger', 'Data tidak ditemukan.');
    }

    /**
     * RESTORE: Mengembalikan data dari Tong Sampah
     */
    public function restore($id)
    {
        // Cari role user (meskipun sudah di soft delete, masih ada di DB)
        $roleUser = DB::table('role_user')->where('idrole_user', $id)->first();
        
        if ($roleUser) {
            // 1. Restore User
            DB::table('user')->where('iduser', $roleUser->iduser)->update([
                'deleted_at' => null
            ]);
            
            // 2. Restore SEMUA Role milik user ini
            DB::table('role_user')->where('iduser', $roleUser->iduser)->update([
                'status' => 1,
                'deleted_at' => null
            ]);

            return redirect()->route('dashboard.admin.role-user.index', ['trash' => 1])
                ->with('success', '♻️ User berhasil dipulihkan dan Aktif kembali.');
        }

        return back()->with('danger', 'Gagal memulihkan user. Data mungkin tidak ditemukan.');
    }
}