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

// ===== Dokter Controllers =====
use App\Http\Controllers\Dokter\{
    RekamMedisController as DokterRekamMedisController,
    PasienController as DokterPasienController,
    ProfilController as DokterProfilController
};

// ===== Pemilik Controllers =====
use App\Http\Controllers\Pemilik\{
    PemilikDashboardController,
    DaftarPetController,
    ProfilController,
    RekamMedisController,
    ReservasiController
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
        Route::get('jenis-hewan/{id}/restore', [JenisHewanController::class, 'restore'])
            ->name('jenis-hewan.restore');
        Route::resource('ras-hewan', RasHewanController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('ras-hewan/{id}/restore', [RasHewanController::class, 'restore'])
            ->name('ras-hewan.restore');
        Route::resource('kategori', KategoriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('kategori/{id}/restore', [KategoriController::class, 'restore'])
            ->name('kategori.restore');
        Route::resource('kategori-klinis', KategoriKlinisController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('kategori-klinis/{id}/restore', [KategoriKlinisController::class, 'restore'])
            ->name('kategori-klinis.restore');
        Route::resource('kode-tindakan-terapi', KodeTindakanTerapiController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('kode-tindakan-terapi/{id}/restore', [KodeTindakanTerapiController::class, 'restore'])
            ->name('kode-tindakan-terapi.restore');

        // Data Pemilik & Pet
        Route::resource('pemilik', AdminPemilikController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('pemilik/{id}/restore', [AdminPemilikController::class, 'restore'])->name('pemilik.restore');
        Route::get('api/get-ras/{idJenis}', [AdminPetController::class, 'getRasByJenis'])->name('api.get-ras');
        Route::resource('pet', AdminPetController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('pet/{id}/restore', [AdminPetController::class, 'restore'])->name('pet.restore');

        // Role & User
        Route::resource('role', RoleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('role-user', UserRoleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::post('role-user/{id}/restore', [UserRoleController::class, 'restore'])
            ->name('role-user.restore');
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
        // Route Resource Rekam Medis (Kecuali Destroy)
        Route::resource('rekam-medis', PerawatRekamMedisController::class)->except(['destroy']);

        // Fitur Tambahan Rekam Medis
        Route::get('rekam-medis/{idreservasi}/panggil', [PerawatRekamMedisController::class, 'panggil'])->name('rekam-medis.panggil');
        Route::get('rekam-medis/{idreservasi}/batal', [PerawatRekamMedisController::class, 'batal'])->name('rekam-medis.batal');

        // CRUD Tindakan
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

        // Rekam Medis
        Route::resource('rekam-medis', DokterRekamMedisController::class)->only(['index', 'show', 'update']);

        // CRUD Detail Tindakan
        Route::post('rekam-medis/{id}/tambah-tindakan', [DokterRekamMedisController::class, 'tambahTindakan'])->name('rekam-medis.tambah-tindakan');
        Route::put('rekam-medis/update-tindakan/{iddetail}', [DokterRekamMedisController::class, 'updateTindakan'])->name('rekam-medis.update-tindakan');
        Route::delete('rekam-medis/hapus-tindakan/{iddetail}', [DokterRekamMedisController::class, 'hapusTindakan'])->name('rekam-medis.hapus-tindakan');

        // Data Pasien & Profil
        Route::get('pasien', [DokterPasienController::class, 'index'])->name('pasien.index');
        Route::get('profil', [DokterProfilController::class, 'index'])->name('profil.index');
    });

/*
|--------------------------------------------------------------------------
| Routes Pemilik
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isPemilik'])
    ->prefix('dashboard/pemilik')
    ->as('dashboard.pemilik.') // Titik di sini penting untuk penamaan route
    ->group(function () {

        // 1. Dashboard Utama (Pastikan route ini ada agar LoginController tidak error)
        Route::get('/', [PemilikDashboardController::class, 'index'])->name('home');
        Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard-pemilik');

        // 2. Rekam Medis (HANYA VIEW - Read Only untuk Pemilik)
        Route::resource('rekam-medis', RekamMedisController::class)->only(['index', 'show']);

        // Note: Route 'tambah-tindakan' dan 'hapus-tindakan' dihapus karena Pemilik tidak boleh mengedit RM.
    
        // 3. Data Pet
        Route::resource('daftar-pet', DaftarPetController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // 4. Profil
        Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');

        // 5. Reservasi
        Route::resource('reservasi', ReservasiController::class)->only(['index', 'create', 'store', 'destroy']);
    });