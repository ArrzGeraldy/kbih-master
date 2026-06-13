<?php

use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SesiBimbinganController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboardAdmin'])->name('dashboard');

        // paket
        Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
        Route::get('/paket/create', [PaketController::class, 'create'])->name('paket.create');
        Route::post('/paket', [PaketController::class, 'store'])->name('paket.store');
        Route::get('/paket/{paket}/edit', [PaketController::class, 'edit'])->name('paket.edit');
        Route::put('/paket/{paket}', [PaketController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');

        // orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit-bimbingan', [OrderController::class, 'editBimbingan'])->name('orders.edit-bimbingan');
        Route::put('/orders/{order}/edit-bimbingan', [OrderController::class, 'updateBimbingan'])->name('orders.update-bimbingan');
        Route::get('/orders/{order}/edit-umroh', [OrderController::class, 'editUmroh'])->name('orders.edit-umroh');
        Route::put('/orders/{order}/edit-umroh', [OrderController::class, 'updateUmroh'])->name('orders.update-umroh');

        // dokumen jamaah
        Route::patch('/dokumen/{dokumen}', [DokumenController::class, 'update'])->name('dokumen.update');

        // kelas bimbingan
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('kelas.show');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');

        // sesi bimbingan
        Route::get('/sesi-bimbingan/{sesi}/edit', [SesiBimbinganController::class, 'edit'])->name('sesi_bimbingan.edit');
        Route::put('/sesi-bimbingan/{sesi}', [SesiBimbinganController::class, 'update'])->name('sesi_bimbingan.update');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bimbingan/daftar/{paket}', [FormController::class, 'formBimbinganCreate'])->name('bimbingan.form');
    Route::post('/bimbingan/daftar/{paket}', [FormController::class, 'formBimbinganStore'])->name('bimbingan.store');

    Route::get('/umroh/daftar/{paket}', [FormController::class, 'formUmrohCreate'])->name('umroh.form');
    Route::post('/umroh/daftar/{paket}', [FormController::class, 'formUmrohStore'])->name('umroh.store');
    
    Route::get('/dashboard', [DashboardController::class, 'dashboardUser'])->name('dashboard');

    // order detail
    Route::get('/order/{id}',[OrderController::class, 'showUser']);

    // DP payment via Midtrans Snap
    Route::post('/order/{id}/dp/snap-token', [OrderController::class, 'createDpSnapToken'])->name('order.dp.snap-token');
    Route::post('/order/{id}/dp/success', [OrderController::class, 'markDpSuccess'])->name('order.dp.success');

    // Cicilan payment via Midtrans Snap
    Route::post('/order/{id}/cicilan/snap-token', [PembayaranController::class, 'createCicilanSnapToken'])->name('order.cicilan.snap-token');
    Route::post('/order/{id}/cicilan/success', [PembayaranController::class, 'markCicilanSuccess'])->name('order.cicilan.success');
    
    // pembyaran
    Route::get('/order/{id}/pembayaran/history', [PembayaranController::class, 'showHistory'])->name('order.pembayaran.history');
   
    // kelas
    Route::get('/order/{id}/kelas', [KelasController::class, 'showUser'])->name('order.kelas');
});

require __DIR__.'/auth.php';
