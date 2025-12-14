<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PemilikController extends Controller
{
    private function redirectWithMessage($route, $message, $type = 'success', $params = [])
    {
        return redirect()->route($route, $params)->with($type, $message);
    }

    /**
     * 🟢 Index: Menampilkan Pemilik (Aktif & Sampah)
     */
    public function index(Request $request)
    {
        try {
            // Base Query: Join Pemilik dengan User
            $query = DB::table('pemilik')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->select(
                    'pemilik.*',
                    'user.nama',
                    'user.email',
                    'user.deleted_at as user_deleted_at' // Ambil status hapus user juga
                );

            // 🔹 Filter Mode Sampah vs Aktif
            if ($request->has('trash') && $request->trash == 1) {
                $query->whereNotNull('pemilik.deleted_at');
            } else {
                $query->whereNull('pemilik.deleted_at');
            }

            // Order by nama user
            $pemilikList = $query->orderBy('user.nama', 'asc')->get();

            return view('dashboard.admin.pemilik.index', compact('pemilikList'));

        } catch (\Throwable $e) {
            Log::error("Error fetch pemilik: " . $e->getMessage());
            return back()->with('danger', 'Gagal memuat data pemilik.');
        }
    }

    /** 🟡 Create Form */
    public function create()
    {
        return view('dashboard.admin.pemilik.create');
    }

    /** 🟢 Store: Simpan User & Pemilik Baru */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            'no_wa'    => 'required|string|max:20',
            'alamat'   => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1. Insert ke Tabel User & Ambil ID-nya
            $idUser = DB::table('user')->insertGetId([
                'nama'       => $request->nama,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                // 'created_at' => now(),
            ]);

            // 2. Insert ke Tabel Pemilik
            DB::table('pemilik')->insert([
                'iduser' => $idUser,
                'no_wa'  => $request->no_wa,
                'alamat' => $request->alamat,
                // 'created_at' => now(),
            ]);

            // 3. Assign Role 'Pemilik'
            $roleId = DB::table('role')->where('nama_role', 'Pemilik')->value('idrole');
            
            if ($roleId) {
                DB::table('role_user')->insert([
                    'user_id' => $idUser, // Sesuaikan nama kolom foreign key di tabel pivot (user_id atau iduser)
                    'role_id' => $roleId, // Sesuaikan nama kolom foreign key (role_id atau idrole)
                    'status'  => 1
                ]);
            }

            DB::commit();
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ User & Profil Pemilik berhasil dibuat.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error create pemilik: " . $e->getMessage());
            return back()->withInput()->with('danger', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /** ✏️ Edit Form */
    public function edit($id)
    {
        try {
            $pemilik = DB::table('pemilik')
                ->join('user', 'pemilik.iduser', '=', 'user.iduser')
                ->where('pemilik.idpemilik', $id)
                ->whereNull('pemilik.deleted_at') // Pastikan data aktif
                ->select('pemilik.*', 'user.nama', 'user.email', 'user.iduser')
                ->first();

            if (!$pemilik) return back()->with('danger', 'Data tidak ditemukan atau sudah dihapus.');
            
            return view('dashboard.admin.pemilik.edit', compact('pemilik'));
        } catch (\Throwable $e) {
            return back()->with('danger', 'Error memuat data.');
        }
    }

    /** 🔄 Update */
    public function update(Request $request, $id)
    {
        // Ambil data pemilik existing untuk dapatkan ID User
        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();
        if (!$pemilik) abort(404);

        $request->validate([
            'nama'   => 'required|string|max:255',
            // Unique ignore ID User
            'email'  => 'required|email|unique:user,email,' . $pemilik->iduser . ',iduser',
            'no_wa'  => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1. Update Tabel User
            DB::table('user')->where('iduser', $pemilik->iduser)->update([
                'nama'  => $request->nama,
                'email' => $request->email,
                // 'updated_at' => now(),
            ]);

            // 2. Update Tabel Pemilik
            DB::table('pemilik')->where('idpemilik', $id)->update([
                'no_wa'  => $request->no_wa,
                'alamat' => $request->alamat,
                // 'updated_at' => now(),
            ]);

            DB::commit();
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '✅ Data berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('danger', 'Gagal update: ' . $e->getMessage());
        }
    }

    /** 🗑️ Soft Delete */
    public function destroy($id)
    {
        try {
            $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();
            if (!$pemilik) return back()->with('danger', 'Data tidak ditemukan.');

            // Cek Hewan Peliharaan
            $petCount = DB::table('pet')->where('idpemilik', $id)->count();
            if ($petCount > 0) {
                return back()->with('danger', "Gagal! Pemilik ini masih memiliki {$petCount} hewan.");
            }

            DB::beginTransaction();

            // Soft Delete Pemilik
            DB::table('pemilik')->where('idpemilik', $id)->update(['deleted_at' => Carbon::now()]);

            // Soft Delete User (Opsional: Agar user tidak bisa login lagi)
            DB::table('user')->where('iduser', $pemilik->iduser)->update(['deleted_at' => Carbon::now()]);

            DB::commit();
            return back()->with('success', '🗑️ Profil pemilik berhasil dipindahkan ke sampah.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('danger', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /** ♻️ Restore */
    public function restore($id)
    {
        try {
            $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();
            if (!$pemilik) return back()->with('danger', 'Data tidak ditemukan.');

            DB::beginTransaction();

            // Restore Pemilik
            DB::table('pemilik')->where('idpemilik', $id)->update(['deleted_at' => null]);

            // Restore User
            DB::table('user')->where('iduser', $pemilik->iduser)->update(['deleted_at' => null]);

            DB::commit();
            
            // Redirect ke halaman trash agar user melihat data yang hilang
            return $this->redirectWithMessage('dashboard.admin.pemilik.index', '♻️ Pemilik berhasil dipulihkan.', 'success', ['trash' => 1]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('danger', 'Gagal restore data.');
        }
    }
}