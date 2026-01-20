<?php

use Illuminate\Support\Facades\Route;

// =======================
// Auth Controller
// =======================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangAtkController;
// =======================
// App Controllers
// =======================
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\PermintaanAtkController;
use App\Http\Controllers\StokOpnameController;

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
    | DASHBOARD UTAMA
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Peminjaman
    |--------------------------------------------------------------------------
    */
    // Route::resource('peminjaman', PeminjamanController::class);
    // Route::put(
    //     'peminjaman/{peminjaman}/kembalikan',
    //     [PeminjamanController::class, 'kembalikan']
    // )->name('peminjaman.kembalikan');

    /*
    |--------------------------------------------------------------------------
    | Barang
    |--------------------------------------------------------------------------
    */
    Route::resource('barang', BarangAtkController::class);
    Route::post('barang/import', [BarangController::class, 'import'])
        ->name('barang.import');
    Route::get('/barang-atk/{barang}/riwayat', [BarangAtkController::class, 'riwayat'])
        ->name('barang.riwayat');

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
    Route::post('/{permintaan}/proses', [PermintaanAtkController::class, 'proses'])
        ->name('permintaan.proses');

    /*
    |--------------------------------------------------------------------------
    | Stok Opname
    |--------------------------------------------------------------------------
    */
    Route::resource('stok-opname', StokOpnameController::class);
    Route::post('stok-opname/{id}/final', [StokOpnameController::class, 'final'])->name('stok-opname.final');
    
    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});
