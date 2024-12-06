<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminTransaksiPembayaranController;
use App\Http\Controllers\AdminTransaksiPengeluaranController;
use App\Http\Controllers\AdminTransaksiPemasukkanController;
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
use App\Http\Controllers\MetodeTransaksiController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\SumberSeluruhController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\RincianTransaksiController;
use App\Http\Controllers\RekapBulananController;

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
Route::get('/admin/keuangan/transaksi/create-pembayaran',[AdminTransaksiPembayaranController::class, 'view']);
Route::get('/admin/keuangan/transaksi/create-pemasukkan',[AdminTransaksiPemasukkanController::class, 'view']);
Route::get('/admin/keuangan/transaksi/create-pengeluaran',[AdminTransaksiPengeluaranController::class, 'view']);
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
Route::resource('metode-transaksi', MetodeTransaksiController::class);
Route::get('/pemasukan/metode/{metode}', [PemasukanController::class, 'showByMetode'])->name('pemasukan.metode');

Route::resource('pemasukan', PemasukanController::class);
// Route::resource('pengeluaran', PengeluaranController::class);
// Route::resource('rincian-transaksi', RincianTransaksiController::class);
// Route::resource('rekap-bulanan', RekapBulananController::class);


Route::resource('sumber-seluruh', SumberSeluruhController::class);
Route::get('sumber-seluruh/detail/{metode_transaksi}', [SumberSeluruhController::class, 'detailByMetode'])->name('sumber-seluruh.detail');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
