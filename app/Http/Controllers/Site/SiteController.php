<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

class SiteController extends \App\Http\Controllers\Controller
{
    public function home()
    {
        return view('site.welcome');
    }
    public function organizations()
    {
        return view('site.organizations');
    }
    public function visi()
    {
        return view('site.visi');
    }

    public function layanan()
    {
        return view('site.layanan');
    }
    
}
