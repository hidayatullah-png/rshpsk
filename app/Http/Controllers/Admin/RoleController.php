<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::orderBy('idrole', 'asc')->get();
        return view('dashboard.admin.role.index', compact('role'));
    }
}