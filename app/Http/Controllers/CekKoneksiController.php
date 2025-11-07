<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;

class CekKoneksiController extends Controller
{
    // 🔹 Tes koneksi database dasar
    public function index()
    {
        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            return view('cek_koneksi.index', compact('dbName'));
        } catch (\Exception $e) {
            return view('cek_koneksi.gagal', ['error' => $e->getMessage()]);
        }
    }

    // 🔹 Menampilkan data tabel utama
    public function data()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        $assignments = RoleUser::with(['user', 'role'])->get();

        // hitung total baris tiap tabel
        $counts = [
            'user' => $users->count(),
            'role' => $roles->count(),
            'role_user' => $assignments->count(),
        ];

        return view('cek_koneksi.data', compact('users', 'roles', 'assignments', 'counts'));
    }
}