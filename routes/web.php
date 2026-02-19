<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangAtkController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\PermintaanAtkController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\StokOpnameController;

/*
|--------------------------------------------------------------------------
| Guest Routes
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
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Pegawai
    |--------------------------------------------------------------------------
    */
    Route::resource('pegawai', PegawaiController::class);
    Route::post('pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');

    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */
    Route::resource('profil', ProfilController::class);
    Route::post('/profile/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('password/edit', [AuthController::class, 'editPassword'])->name('password.edit');
    Route::put('/password/ubah-password', [AuthController::class, 'updatePassword'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Barang ATK
    |--------------------------------------------------------------------------
    */
    Route::get('/barang/search', [BarangAtkController::class, 'search'])->name('barang.search');
    Route::resource('barang', BarangAtkController::class);
    Route::post('barang/import', [BarangAtkController::class, 'import'])->name('barang.import');
    Route::get('barang/{barang}/riwayat', [BarangAtkController::class, 'riwayat'])->name('barang.riwayat');

    /*
    |--------------------------------------------------------------------------
    | Mutasi
    |--------------------------------------------------------------------------
    */
    Route::resource('mutasi', MutasiStokController::class);
    Route::post('mutasi/import', [MutasiStokController::class, 'import'])->name('mutasi.import');

    /*
    |--------------------------------------------------------------------------
    | Permintaan
    |--------------------------------------------------------------------------
    */
    Route::resource('permintaan', PermintaanAtkController::class);
    Route::post('permintaan/{permintaan}/proses',
        [PermintaanAtkController::class, 'proses']
    )->name('permintaan.proses');

    /*
    |--------------------------------------------------------------------------
    | Stok Opname
    |--------------------------------------------------------------------------
    */

    Route::get('stok-opname/template',
        [StokOpnameController::class, 'downloadTemplate']
    )->name('stok-opname.template');


    Route::resource('stok-opname', StokOpnameController::class);

    Route::post('stok-opname/{id}/final',
        [StokOpnameController::class, 'final']
    )->name('stok-opname.final');

    Route::post('stok-opname/import',
        [StokOpnameController::class, 'importExcel']
    )->name('stok-opname.import');

    Route::get('stok-opname/{id}/export-pdf',
        [StokOpnameController::class, 'exportPdf']
    )->name('stok-opname.export-pdf');

    Route::get('stok-opname/{id}/export-excel',
        [StokOpnameController::class, 'exportExcel']
    )->name('stok-opname.export-excel');

    // ✅ TEMPLATE DOWNLOAD (INI YANG BENAR)

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
