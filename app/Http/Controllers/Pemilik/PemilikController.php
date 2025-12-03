<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;

class PemilikController extends Controller
{
    public function index()
    {
        return view('dashboard.pemilik.index');
    }
}