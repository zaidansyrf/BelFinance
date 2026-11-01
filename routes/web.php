<?php

use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\AdminKeuanganInfoProfileController;
use App\Http\Controllers\AdminLaporanKeuanganController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerBerandaController;
use App\Http\Controllers\OwnerInfoProfileController;
use App\Http\Controllers\OwnerLaporanKeuanganController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SaranUsahaController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UangMasukController;
use Illuminate\Support\Facades\Route;

/*
| Hak akses (middleware 'role:...'): owner selalu lolos, jadi owner bisa membuka semua halaman.
| Login (POST), logout, dan reset password ada di routes/auth.php.
*/

Route::fallback(fn () => response()->view('errors.404', [], 404));

// ---------------------------------------------------------------------------
// Publik
// ---------------------------------------------------------------------------
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')->name('login-belfinance');

// ---------------------------------------------------------------------------
// Semua role: profil & ganti password
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:keuangan,kasir'])->group(function () {
    Route::get('/keuangan/info-profile', [AdminKeuanganInfoProfileController::class, 'view'])->name('info-profile');
    Route::post('/keuangan/info-profile/update', [AdminKeuanganInfoProfileController::class, 'update'])->name('info-profile.update');
    Route::post('/profile/change-password', [AdminKeuanganInfoProfileController::class, 'changePassword'])->name('info-profile.update-password');
});

// ---------------------------------------------------------------------------
// Keuangan (+ owner)
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:keuangan'])->prefix('keuangan')->group(function () {
    Route::get('/dashboard', [AdminKeuanganController::class, 'view'])->name('dashboard');
    Route::get('/laporan-keuangan', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan');

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

    // Menu & stok
    Route::get('/menu', [ItemController::class, 'search'])->name('menu.search');
    Route::post('/menu', [ItemController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit', [ItemController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [ItemController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [ItemController::class, 'destroy'])->name('menu.destroy');
    Route::post('/menu/deskripsi-ai', [AiController::class, 'deskripsiMenu'])
        ->middleware('throttle:20,1')->name('ai.deskripsi-menu');

    // Kategori
    Route::resource('kategori/sumber-masuk', SourceController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::resource('kategori/sumber-keluar', BillController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
});

// ---------------------------------------------------------------------------
// Keuangan & owner bersama
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/laporan/export-pdf', [AdminLaporanKeuanganController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');

    // Tanya AI: keuangan dibatasi per hari (lihat AiController::BATAS_HARIAN_KEUANGAN)
    Route::get('/ai/asisten', [AiController::class, 'asisten'])->name('ai.asisten');
    Route::post('/ai/tanya', [AiController::class, 'tanya'])->middleware('throttle:30,1')->name('ai.tanya');
});

// ---------------------------------------------------------------------------
// Kasir (+ owner)
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/pos', [KasirController::class, 'pos'])->name('pos');
    Route::post('/pos', [KasirController::class, 'checkout'])->name('checkout');
    Route::get('/beranda', [KasirController::class, 'beranda'])->name('beranda');

    // Shift: buka dengan modal awal, tutup dengan hitung kas
    Route::get('/shift', [ShiftController::class, 'show'])->name('shift');
    Route::post('/shift/buka', [ShiftController::class, 'buka'])->name('shift.buka');
    Route::post('/shift/tutup', [ShiftController::class, 'tutup'])->name('shift.tutup');
});

// Struk: kasir hanya miliknya sendiri, keuangan dan owner semua transaksi
Route::get('/struk/{income}', [KasirController::class, 'struk'])
    ->middleware(['auth', 'role:kasir,keuangan'])->name('kasir.struk');

// ---------------------------------------------------------------------------
// Khusus owner
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::prefix('owner')->group(function () {
        Route::get('/beranda', [OwnerBerandaController::class, 'view'])->name('owner-beranda');
        Route::get('/laporan', [OwnerLaporanKeuanganController::class, 'view'])->name('laporan-owner');
        Route::get('/saran', [SaranUsahaController::class, 'index'])->name('saran.index');
        Route::get('/info-profile', [OwnerInfoProfileController::class, 'view'])->name('owner-info-profile');

        // Kelola akun pegawai (kasir & keuangan)
        Route::resource('pegawai', PegawaiController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Analisis dashboard AI (keuangan tidak punya akses)
    Route::post('/ai/insight', [AiController::class, 'insight'])->middleware('throttle:10,1')->name('ai.insight');
    Route::post('/ai/saran-usaha', [AiController::class, 'saranUsaha'])->middleware('throttle:10,1')->name('ai.saran-usaha');
});

require __DIR__.'/auth.php';
