<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    RekamMedisController as AdminRekamMedisController,
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

// ===== Perawat Controllers =====
use App\Http\Controllers\Perawat\{
    RekamMedisController as PerawatRekamMedisController,
    PasienController as PerawatPasienController,
    ProfilController as PerawatProfilController
};

// ===== Dokter Controllers (BARU) =====
use App\Http\Controllers\Dokter\{
    RekamMedisController as DokterRekamMedisController,
    PasienController as DokterPasienController,         
    ProfilController as DokterProfilController          
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
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard-admin');
        
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
        Route::resource('rekam-medis', AdminRekamMedisController::class)
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
        Route::get('/dashboard', [ResepsionisDashboardController::class, 'index'])->name('dashboard-resepsionis');
        
        // Registrasi
        Route::resource('registrasi-pemilik', ResepsionisPemilikController::class)->only(['create', 'store']);
        Route::get('api/get-ras/{idJenis}', [ResepsionisPetController::class, 'getRasByJenis'])->name('api.get-ras');
        Route::resource('registrasi-pet', ResepsionisPetController::class)->only(['create', 'store']);

        // Temu Dokter
        Route::resource('temu-dokter', ResepsionisTemuDokterController::class)->except(['show', 'edit']);
    });

/*
|--------------------------------------------------------------------------
| Routes Perawat
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isPerawat'])
    ->prefix('dashboard/perawat')
    ->as('dashboard.perawat.')
    ->group(function () {
        // Redirect dashboard ke rekam medis (Opsional, jika kamu mau tetap ada dashboard bisa dihapus)
        // Route::redirect('/', '/dashboard/perawat/rekam-medis'); // Hapus baris ini jika ingin tetap pakai PerawatDashboardController

        // Route Resource Rekam Medis (Kecuali Destroy)
        Route::resource('rekam-medis', PerawatRekamMedisController::class)->except(['destroy']);
        
        // Fitur Tambahan Rekam Medis (Panggil, Batal, & Tindakan)
        Route::get('rekam-medis/{idreservasi}/panggil', [PerawatRekamMedisController::class, 'panggil'])->name('rekam-medis.panggil');
        Route::get('rekam-medis/{idreservasi}/batal', [PerawatRekamMedisController::class, 'batal'])->name('rekam-medis.batal');
        
        // CRUD Tindakan (Untuk fitur tambah tindakan di detail/create)
        Route::post('rekam-medis/{id}/tambah-tindakan', [PerawatRekamMedisController::class, 'tambahTindakan'])->name('rekam-medis.tambah-tindakan');
        Route::put('rekam-medis/update-tindakan/{iddetail}', [PerawatRekamMedisController::class, 'updateTindakan'])->name('rekam-medis.update-tindakan');
        Route::delete('rekam-medis/hapus-tindakan/{iddetail}', [PerawatRekamMedisController::class, 'hapusTindakan'])->name('rekam-medis.hapus-tindakan');

        // Pasien & Profil
        Route::get('pasien', [PerawatPasienController::class, 'index'])->name('pasien.index');
        Route::get('profil', [PerawatProfilController::class, 'index'])->name('profil.index');
    });

/*
|--------------------------------------------------------------------------
| Routes Dokter
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isDokter'])
    ->prefix('dashboard/dokter')
    ->as('dashboard.dokter.')
    ->group(function () {
        // Redirect dashboard ke rekam medis
        Route::redirect('/', '/dashboard/dokter/rekam-medis');
        Route::redirect('/dashboard', '/dashboard/dokter/rekam-medis');

        // Rekam Medis (Index, Show, Update Header)
        Route::resource('rekam-medis', DokterRekamMedisController::class)->only(['index', 'show', 'update']);

        // CRUD Detail Tindakan (Fitur Utama Dokter)
        Route::post('rekam-medis/{id}/tambah-tindakan', [DokterRekamMedisController::class, 'tambahTindakan'])->name('rekam-medis.tambah-tindakan');
        Route::put('rekam-medis/update-tindakan/{iddetail}', [DokterRekamMedisController::class, 'updateTindakan'])->name('rekam-medis.update-tindakan');
        Route::delete('rekam-medis/hapus-tindakan/{iddetail}', [DokterRekamMedisController::class, 'hapusTindakan'])->name('rekam-medis.hapus-tindakan');

        // Data Pasien (Hanya Index)
        Route::get('pasien', [DokterPasienController::class, 'index'])->name('pasien.index');

        // Profil Saya (Hanya Index)
        Route::get('profil', [DokterProfilController::class, 'index'])->name('profil.index');
    });