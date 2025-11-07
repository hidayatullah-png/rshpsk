<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $user = User::with(['role' => function ($query) {
        $query->wherePivot('status', 1);}])->orderBy('nama')->get();
        $role = Role::orderBy('nama_role')->get();

        return view('dashboard.admin.role-user.index', compact('user', 'role'));
    }
}
