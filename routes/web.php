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
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;



// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/', [AuthenticatedSessionController::class,'create'])->name('login-belfinance');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');


Route::middleware(['web'])->group(function () {
    Route::get('/keuangan/dashboard',[AdminKeuanganController::class, 'view'])->name(('dashboard'));
    Route::get('/keuangan/pembayaran',[PembayaranController::class, 'index']);
    Route::get('/keuangan/pembayaran/create', [KeuanganCreatePembayaranController::class, 'view'])->name('pembayaran.create');
    Route::resource('/keuangan/pembayaran', PembayaranController::class);
    Route::get('/keuangan/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::delete('/keuangan/pembayaran/{income}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    Route::get('/keuangan/uang-masuk',[UangMasukController::class, 'index']);
    Route::delete('/keuangan/uang-masuk/{income}', [UangMasukController::class, 'destroy'])->name('income.destroy');
    Route::get('/keuangan/uang-masuk/', [UangMasukController::class, 'search'])->name('uang-masuk.search');
    Route::resource('/keuangan/uang-masuk', UangMasukController::class);
    Route::get('/keuangan/uang-masuk/', [UangMasukController::class, 'search'])->name('uang-masuk.search');

    Route::resource('/keuangan/pengeluaran', ExpenseController::class);
    Route::get('/keuangan/pengeluaran/', [ExpenseController::class, 'search'])->name('expenses.search');
    // hapus pengeluaran
    Route::delete('/keuangan/pengeluaran/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    // update
    Route::put('/keuangan/pengeluaran/{expense}/edit', [ExpenseController::class, 'update'])->name('expenses.update');
    // show
    Route::post('/keuangan/pengeluaran/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');

    Route::resource('/keuangan/menu', ItemController::class);
    Route::get('/keuangan/menu/', [ItemController::class, 'search'])->name('menu.search');
    Route::get('/keuangan/menu/{id}/edit', [ItemController::class, 'edit'])->name('menu.edit');
    Route::put('/keuangan/menu/{id}', [ItemController::class, 'update'])->name('menu.update');
    Route::get('keuangan/menu-terlaris', [ItemController::class, 'chart'])->name('menu.chart');
    Route::delete('/keuangan/menu/{id}', [ItemController::class, 'destroy'])->name('item.destroy');


    Route::resource('/keuangan/kategori/sumber-masuk', SourceController::class);
    Route::resource('/keuangan/kategori/sumber-keluar', BillController::class);
    //laporan
    Route::get('/keuangan/laporan-keuangan', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan');
    Route::get('/laporan/export-pdf', [AdminLaporanKeuanganController::class, 'exportPdf'])->name('laporan.export-pdf');
    //Info Profile
    Route::get('/keuangan/info-profile',[AdminKeuanganInfoProfileController::class, 'view']);
    Route::get('/keuangan/info-profile/edit', [AdminKeuanganInfoProfileController::class, 'edit'])->name('info-profile.edit');
    Route::post('/keuangan/info-profile/update', [AdminKeuanganInfoProfileController::class, 'update'])->name('info-profile.update');
    Route::get('/profile/change-password', [AdminKeuanganInfoProfileController::class, 'changePasswordForm'])->name('info-profile.change-password-form');
    Route::post('/profile/change-password', [AdminKeuanganInfoProfileController::class, 'changePassword'])->name('info-profile.update-password');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::middleware(['web', 'auth'])->group(function () {
    // beranda owner
Route::get('/owner/beranda',[OwnerBerandaController::class, 'view'])->name('owner-beranda');
// laporan owner
Route::get('/owner/laporan', [OwnerLaporanKeuanganController::class, 'view'])->name('laporan-owner');
// info profile owner
Route::get('/owner/info-profile',[OwnerInfoProfileController::class, 'view'])->name('owner-info-profile');
});
require __DIR__.'/auth.php';
