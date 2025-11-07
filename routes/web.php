<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Dokter\DokterDashboardController;
use App\Http\Controllers\Perawat\PerawatDashboardController;
use App\Http\Controllers\Resepsionis\ResepsionisDashboardController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\CekKoneksiController;
use App\Http\Controllers\Admin\PemilikController;
use App\Http\Controllers\Admin\JenisHewanController;
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KodeTindakanTerapiController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/cek-koneksi', [CekKoneksiController::class, 'index'])->name('cek.koneksi');
Route::get('/cek-data', [CekKoneksiController::class, 'data'])->name('cek.data');

Route::get('/', [SiteController::class, 'home'])->name('site.home');

Auth::routes();

Route::get('/organizations', function () {
    return view('site.organizations');
});

Route::get('/visi', function () {
    return view('site.visi');
});

Route::get('/layanan', function () {
    return view('site.layanan');
});

// Akses Administrator
Route::middleware('isAdministrator')->prefix('dashboard/admin')->as('dashboard.admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])
        ->name('dashboard-admin');

    // Resource Routes
    Route::resource('jenis-hewan', JenisHewanController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('ras-hewan', RasHewanController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('kategori', KategoriController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('kategori-klinis', KategoriKlinisController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('kode-tindakan-terapi', KodeTindakanTerapiController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('pet', PetController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('role', RoleController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('role-user', UserRoleController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);
});

Route::middleware('isResepsionis')->group(function () {
    Route::get('/resepsionis/dashboard', [ResepsionisDashboardController::class, 'index'])->name('dashboard.resepsionis.dashboard-resepsionis');
});