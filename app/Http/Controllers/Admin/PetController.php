<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['pemilik', 'ras.jenis'])->orderBy('idpet', 'asc')->get();
        return view('dashboard.admin.pet.index', compact('pets'));
    }
}