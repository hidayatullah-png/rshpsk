<?php

use Illuminate\Support\Facades\Route;

// ===== Global Controllers =====
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CekKoneksiController;
use App\Http\Controllers\Site\SiteController;

// ===== Admin Controllers =====
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    JenisHewanController,
    RasHewanController,
    KategoriController,
    KategoriKlinisController,
    KodeTindakanTerapiController,
    RekamMedisController,
    PetController as AdminPetController,
    PemilikController as AdminPemilikController,
    RoleController,
    UserController,
    UserRoleController
};

// ===== Resepsionis Controllers =====
use App\Http\Controllers\Resepsionis\{
    ResepsionisDashboardController,
    PemilikController as ResepsionisPemilikController,
    PetController as ResepsionisPetController,
    TemuDokterController as ResepsionisTemuDokterController
};



/*
|--------------------------------------------------------------------------
| Routes Umum (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [SiteController::class, 'home'])->name('site.home');

Route::view('/organizations', 'site.organizations')->name('site.organizations');
Route::view('/visi', 'site.visi')->name('site.visi');
Route::view('/layanan', 'site.layanan')->name('site.layanan');

// Tes koneksi / debugging
Route::get('/cek-koneksi', [CekKoneksiController::class, 'index'])->name('cek.koneksi');
Route::get('/cek-data', [CekKoneksiController::class, 'data'])->name('cek.data');

// Auth routes
Auth::routes();

/*
|--------------------------------------------------------------------------
| Routes Administrator
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdministrator'])
    ->prefix('dashboard/admin')
    ->as('dashboard.admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard-admin');

        // Master Data
        Route::resource('jenis-hewan', JenisHewanController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('ras-hewan', RasHewanController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('kategori', KategoriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('kategori-klinis', KategoriKlinisController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('kode-tindakan-terapi', KodeTindakanTerapiController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Data Pemilik & Pet
        Route::resource('pemilik', AdminPemilikController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('api/get-ras/{idJenis}', [AdminPetController::class, 'getRasByJenis'])->name('api.get-ras');
        Route::resource('pet', AdminPetController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Role & User
        Route::resource('role', RoleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('role-user', UserRoleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('user', UserController::class)->except(['index', 'show']);

        // Rekam Medis
        Route::resource('rekam-medis', RekamMedisController::class)
            ->parameters(['rekam-medis' => 'rekam_medis'])
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });


/*
|--------------------------------------------------------------------------
| Routes Resepsionis
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isResepsionis'])
    ->prefix('dashboard/resepsionis')
    ->as('dashboard.resepsionis.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [ResepsionisDashboardController::class, 'index'])->name('dashboard-resepsionis');

        // Registrasi Pemilik
        Route::resource('registrasi-pemilik', ResepsionisPemilikController::class)->only(['create', 'store']);

        // 🔹 TAMBAHAN: Route API untuk Dropdown Ras (Agar AJAX di Form Create Berfungsi)
        Route::get('api/get-ras/{idJenis}', [ResepsionisPetController::class, 'getRasByJenis'])->name('api.get-ras');

        // Registrasi Pet
        // Saya tambahkan 'index' karena tombol "Kembali" di form mengarah ke route index
        Route::resource('registrasi-pet', ResepsionisPetController::class)->only(['index', 'create', 'store']);

        // Temu Dokter
        Route::resource('temu-dokter', ResepsionisTemuDokterController::class)->except(['show', 'edit']);
    });
