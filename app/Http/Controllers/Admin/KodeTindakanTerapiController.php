<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KodeTindakanTerapi;


class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        $KodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->get();
        return view('dashboard.admin.kode_tindakan_terapi.index', compact('KodeTindakanTerapi'));
    }
}