<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RasHewan;
use App\Models\JenisHewan; 

class RasHewanController extends Controller
{
    public function index ()
    {
        $jenisHewanRas = JenisHewan::with('ras')->get();
        return view('dashboard.admin.ras_hewan.index', compact('jenisHewanRas'));
    }

}