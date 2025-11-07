<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ 2. Ambil user
        $user = User::with(['roleUser.role'])
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
        $activeRoleUser = $user->roleUser->firstWhere('status', 1);

        if (!$activeRoleUser) {
            return back()->withErrors(['role' => 'Akun Anda tidak memiliki role aktif.'])->withInput();
        }

        $activeRole = $activeRoleUser->role;

        // ✅ 5. Login user
        Auth::login($user);

        // ✅ 6. Simpan ke session
        $request->session()->put([
            'user_id' => $user->iduser,
            'user_name' => $user->nama,
            'user_email' => $user->email,
            'user_role' => $activeRole->idrole ?? null,
            'user_role_name' => $activeRole->nama_role ?? 'Not found',
            'user_status' => $activeRoleUser->status ?? 'inactive',
        ]);

        $roleId = $activeRole->idrole ?? null;

        // ✅ 7. Arahkan sesuai role ID
        switch ($roleId) {
            case '1':
                return redirect()->route('dashboard.admin.dashboard-admin')
                    ->with('success', 'Login berhasil!');
            case '2':
                return redirect()->route('dashboard.dokter.dashboard-dokter')
                    ->with('success', 'Login berhasil!');
            case '3':
                return redirect()->route('dashboard.perawat.dashboard-perawat')
                    ->with('success', 'Login berhasil!');
            case '4':
                return redirect()->route('dashboard.resepsionis.dashboard-resepsionis')
                    ->with('success', 'Login berhasil!');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak dikenali.');
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
