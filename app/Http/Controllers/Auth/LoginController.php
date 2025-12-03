<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use App\Models\Role; // Hapus jika tidak dipakai langsung

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ✅ 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // ✅ 2. Ambil user
        $user = User::with(['roleUsers.role'])
            ->where('email', $request->input('email'))
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // ✅ 3. Verifikasi password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        // ✅ 4. Ambil role aktif dari pivot role_user
        $activeRoleUser = $user->roleUsers->firstWhere('status', 1);

        if (!$activeRoleUser) {
            return back()->withErrors(['role' => 'Akun Anda tidak memiliki role aktif.'])->withInput();
        }

        $activeRole = $activeRoleUser->role;

        // ✅ 5. Login user
        Auth::login($user);

        // ✅ 6. Simpan ke session (Opsional jika Anda memakainya di view/middleware)
        $request->session()->put([
            'user_id' => $user->iduser,
            'user_name' => $user->nama, // Pastikan kolom di DB adalah 'nama' atau 'name'
            'user_email' => $user->email,
            'user_role' => $activeRole->idrole ?? null,
            'user_role_name' => $activeRole->nama_role ?? 'Not found',
            'user_status' => $activeRoleUser->status ?? 'inactive',
        ]);

        $roleId = $activeRole->idrole ?? null;

        // ✅ 7. Arahkan sesuai role ID
        switch ($roleId) {
            case '1': // Administrator
                return redirect()->route('dashboard.admin.dashboard-admin')
                    ->with('success', 'Login berhasil! Selamat datang Admin.');
            
            case '2': // Dokter
                return redirect()->route('dashboard.dokter.rekam-medis.index')
                    ->with('success', 'Login berhasil! Selamat datang Dokter.');
            
            case '3': // Perawat
                return redirect()->route('dashboard.perawat.rekam-medis.index')
                    ->with('success', 'Login berhasil! Selamat bekerja.');
            
            case '4': // Resepsionis
                return redirect()->route('dashboard.resepsionis.dashboard-resepsionis')
                    ->with('success', 'Login berhasil! Selamat datang Resepsionis.');

            // 🔥 TAMBAHAN BARU: PEMILIK (ROLE ID = 6)
            case '6': 
                // Asumsi Pemilik diarahkan ke halaman utama website (site.home) atau dashboard khusus pemilik (jika ada)
                return redirect()->route('site.home')
                    ->with('success', 'Selamat datang kembali, Pemilik.');
            
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak dikenali atau Anda tidak memiliki akses.');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}