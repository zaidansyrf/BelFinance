<?php

use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\AdminKeuanganInfoProfileController;
use App\Http\Controllers\AdminLaporanKeuanganController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OwnerBerandaController;
use App\Http\Controllers\OwnerInfoProfileController;
use App\Http\Controllers\OwnerLaporanKeuanganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UangMasukController;
use Illuminate\Support\Facades\Route;

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login-belfinance');
// Login (POST), logout, dan reset password ada di routes/auth.php

// Halaman admin keuangan: wajib login dan role keuangan
Route::middleware(['auth', 'role:keuangan'])->prefix('keuangan')->group(function () {
    Route::get('/dashboard', [AdminKeuanganController::class, 'view'])->name('dashboard');

    // Pembayaran (pesanan)
    Route::resource('pembayaran', PembayaranController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // Uang masuk
    Route::get('/uang-masuk', [UangMasukController::class, 'search'])->name('uang-masuk.search');
    Route::post('/uang-masuk', [UangMasukController::class, 'store'])->name('uang-masuk.store');
    Route::delete('/uang-masuk/{income}', [UangMasukController::class, 'destroy'])->name('income.destroy');

    // Pengeluaran
    Route::get('/pengeluaran', [ExpenseController::class, 'search'])->name('expenses.search');
    Route::get('/pengeluaran/create', [ExpenseController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [ExpenseController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/pengeluaran/{expense}/edit', [ExpenseController::class, 'edit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{expense}/edit', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/pengeluaran/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Menu
    Route::get('/menu', [ItemController::class, 'search'])->name('menu.search');
    Route::post('/menu', [ItemController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit', [ItemController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [ItemController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [ItemController::class, 'destroy'])->name('menu.destroy');

    // Kategori
    Route::resource('kategori/sumber-masuk', SourceController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::resource('kategori/sumber-keluar', BillController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    // Laporan
    Route::get('/laporan-keuangan', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan');
});

// Dipakai bersama oleh keuangan dan owner (export PDF & profil)
Route::middleware(['auth', 'role:keuangan,owner'])->group(function () {
    Route::get('/laporan/export-pdf', [AdminLaporanKeuanganController::class, 'exportPdf'])->name('laporan.export-pdf');

    Route::get('/keuangan/info-profile', [AdminKeuanganInfoProfileController::class, 'view'])->name('info-profile');
    Route::post('/keuangan/info-profile/update', [AdminKeuanganInfoProfileController::class, 'update'])->name('info-profile.update');
    Route::post('/profile/change-password', [AdminKeuanganInfoProfileController::class, 'changePassword'])->name('info-profile.update-password');
});

// Halaman owner: wajib login dan role owner
Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/beranda', [OwnerBerandaController::class, 'view'])->name('owner-beranda');
    Route::get('/laporan', [OwnerLaporanKeuanganController::class, 'view'])->name('laporan-owner');
    Route::get('/info-profile', [OwnerInfoProfileController::class, 'view'])->name('owner-info-profile');
});

require __DIR__.'/auth.php';
