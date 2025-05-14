<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\DetailPemasukkanController;
use App\Http\Controllers\KeuanganCreatePembayaranController;
use App\Http\Controllers\KeuanganCreatePengeluaranController;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\AdminLaporanKeuanganController;
use App\Http\Controllers\AdminKeuanganLaporanPembayaranController;
use App\Http\Controllers\AdminKeuanganLaporanPemasukkanController;
use App\Http\Controllers\AdminKeuanganLaporanPengeluaranController;
use App\Http\Controllers\AdminKeuanganLaporanSumberController;
use App\Http\Controllers\AdminKeuanganInfoProfileController;
use App\Http\Controllers\AdminKeuanganMenuController;
use App\Http\Controllers\OwnerBerandaController;
use App\Http\Controllers\OwnerLaporanKeuanganController;
use App\Http\Controllers\OwnerInfoProfileController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\TagihanController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExpenseController;


// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login-belfinance', function () {
    return view("login-belfinance");
});

Route::get('/keuangan/dashboard',[AdminKeuanganController::class, 'view']);
Route::get('/keuangan/pembayaran',[PembayaranController::class, 'index']);
Route::get('/keuangan/uang-masuk',[UangMasukController::class, 'index']);
Route::resource('/keuangan/uang-masuk', UangMasukController::class);
Route::get('/item/menu', [ItemController::class, 'index'])->name('item.index');

Route::resource('/keuangan/pembayaran', PembayaranController::class);
Route::resource('/keuangan/pengeluaran', ExpenseController::class);
Route::get('/keuangan/detail-pemasukkan',[DetailPemasukkanController::class, 'view']);
Route::resource('/keuangan/kategori/sumber-masuk', SourceController::class);
Route::resource('/keuangan/kategori/sumber-keluar', BillController::class);
Route::get('/keuangan/info-profile',[AdminKeuanganInfoProfileController::class, 'view']);
Route::get('/owner/beranda',[OwnerBerandaController::class, 'view']);
Route::get('/owner/laporan-keuangan',[OwnerLaporanKeuanganController::class, 'view']);
Route::get('/owner/info-profile',[OwnerInfoProfileController::class, 'view']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/keuangan/menu', ItemController::class);
Route::get('/keuangan/menu/', [ItemController::class, 'search'])->name('menu.search');
Route::get('/keuangan/pengeluaran/', [ExpenseController::class, 'search'])->name('expenses.search');
Route::get('/keuangan/uang-masuk/', [UangMasukController::class, 'search'])->name('uang-masuk.search');
Route::get('/pembayaran/chart', [PembayaranController::class, 'chartIncome']);
Route::get('/pengeluaran/chart', [ExpenseController::class, 'chartExpense']);
Route::get('/keuangan/detail-pesanan/{source_id}', [AdminKeuanganController::class, 'detailPesanan']);
Route::get('/keuangan/detail-pesanan', [PembayaranController::class, 'detail'])->name('pembayaran.detail');


require __DIR__.'/auth.php';
