<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminLaporanKeuanganController;
use App\Http\Controllers\AdminKeuanganLaporanPembayaranController;
use App\Http\Controllers\AdminKeuanganLaporanPemasukkanController;
use App\Http\Controllers\AdminKeuanganLaporanPengeluaranController;
use App\Http\Controllers\AdminKeuanganLaporanSumberController;
use App\Http\Controllers\AdminKeuanganInfoProfileController;
use App\Http\Controllers\AdminKeuanganMenuController;
use App\Http\Controllers\AdminKeuanganKategoriSumberMasukController;
use App\Http\Controllers\AdminKeuanganKategoriSumberKeluarController;
use App\Http\Controllers\OwnerBerandaController;
use App\Http\Controllers\OwnerLaporanKeuanganController;
use App\Http\Controllers\OwnerInfoProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login-belfinance', function () {
    return view("login-belfinance");
});

Route::get('/admin/keuangan/dashboard',[AdminKeuanganController::class, 'view']);
Route::get('/admin/keuangan/transaksi',[AdminTransaksiController::class, 'view']);
// Route::get('/admin/keuangan/laporan-keuangan',[AdminLaporanKeuanganController::class, 'view']);
Route::get('/admin/keuangan/info-profile',[AdminKeuanganInfoProfileController::class, 'view']);
Route::get('/admin/keuangan/menu',[AdminKeuanganMenuController::class, 'view']);
Route::get('/admin/keuangan/kategori/sumber-masuk',[AdminKeuanganKategoriSumberMasukController::class, 'view']);
Route::get('/admin/keuangan/kategori/sumber-keluar',[AdminKeuanganKategoriSumberKeluarController::class, 'view']);
Route::get('/admin/keuangan/laporan-keuangan/pembayaran',[AdminKeuanganLaporanPembayaranController::class, 'view']);
Route::get('/admin/keuangan/laporan-keuangan/pemasukkan',[AdminKeuanganLaporanPemasukkanController::class, 'view']);
Route::get('/admin/keuangan/laporan-keuangan/pengeluaran',[AdminKeuanganLaporanPengeluaranController::class, 'view']);
Route::get('/admin/keuangan/laporan-keuangan/sumber',[AdminKeuanganLaporanSumberController::class, 'view']);
// Route::get('/admin/hutang',[AdminHutangController::class, 'view']);
Route::get('/owner/beranda',[OwnerBerandaController::class, 'view']);
Route::get('/owner/laporan-keuangan',[OwnerLaporanKeuanganController::class, 'view']);
Route::get('/owner/info-profile',[OwnerInfoProfileController::class, 'view']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
