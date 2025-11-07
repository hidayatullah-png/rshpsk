<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriKlinis;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $KategoriKlinis = KategoriKlinis::all();
        return view('dashboard.admin.kategori_klinis.index', compact('KategoriKlinis'));
    }
}