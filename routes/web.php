<?php

use Illuminate\Support\Facades\Route;

// =======================
// Auth Controller
// =======================
use App\Http\Controllers\AuthController;

// =======================
// App Controllers
// =======================
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangAtkController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\PermintaanAtkController;

/*
|--------------------------------------------------------------------------
| Guest Routes (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('loginAction');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerAction'])->name('registerAction');
});

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Routes (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Utama
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Pegawai & Kehadiran
    |--------------------------------------------------------------------------
    */
    Route::resource('pegawai', PegawaiController::class);
    Route::post('pegawai/import', [PegawaiController::class, 'import'])
        ->name('pegawai.import');

    Route::resource('kehadiran', KehadiranController::class);
    Route::post('kehadiran/import', [KehadiranController::class, 'import'])
        ->name('kehadiran.import');

    /*
    |--------------------------------------------------------------------------
    | Pengajuan
    |--------------------------------------------------------------------------
    */
    Route::resource('pengajuan', PengajuanController::class);

    /*
    |--------------------------------------------------------------------------
    | Peminjaman
    |--------------------------------------------------------------------------
    */
    Route::resource('peminjaman', PeminjamanController::class);
    Route::put(
        'peminjaman/{peminjaman}/kembalikan',
        [PeminjamanController::class, 'kembalikan']
    )->name('peminjaman.kembalikan');

    /*
    |--------------------------------------------------------------------------
    | Barang ATK
    |--------------------------------------------------------------------------
    */
    Route::resource('barang', BarangAtkController::class);

    // ✅ IMPORT EXCEL BARANG (FIX)
    Route::post('barang/import', [BarangAtkController::class, 'import'])
        ->name('barang.import');

    // ✅ RIWAYAT BARANG
    Route::get(
        'barang/{barang}/riwayat',
        [BarangAtkController::class, 'riwayat']
    )->name('barang.riwayat');

    /*
    |--------------------------------------------------------------------------
    | Mutasi Stok
    |--------------------------------------------------------------------------
    */
    Route::resource('mutasi', MutasiStokController::class);

    /*
    |--------------------------------------------------------------------------
    | Permintaan ATK
    |--------------------------------------------------------------------------
    */
    Route::resource('permintaan', PermintaanAtkController::class);
    Route::post(
        'permintaan/{permintaan}/proses',
        [PermintaanAtkController::class, 'proses']
    )->name('permintaan.proses');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});
