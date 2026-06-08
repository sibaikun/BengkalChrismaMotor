<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\NotaController;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Semua route butuh login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::resource('kategori', KategoriController::class);

    // Barang / Inventori
    Route::resource('barang', BarangController::class);

    // Servis
    Route::resource('servis', ServisController::class)->parameters([
        'servis' => 'servis'
    ]);

    // Nota
    Route::get('/nota',                  [NotaController::class, 'index'])->name('nota.index');
    Route::get('/nota/buat',             [NotaController::class, 'create'])->name('nota.create');
    Route::post('/nota/preview',         [NotaController::class, 'preview'])->name('nota.preview');
    Route::post('/nota/simpan',          [NotaController::class, 'store'])->name('nota.store');
    Route::get('/nota/{nota}',           [NotaController::class, 'show'])->name('nota.show');
    Route::get('/nota/{nota}/cetak',     [NotaController::class, 'cetak'])->name('nota.cetak');
    Route::get('/nota/{nota}/edit',      [NotaController::class, 'edit'])->name('nota.edit');       // <-- baru
    Route::post('/nota/{nota}/preview-edit', [NotaController::class, 'previewEdit'])->name('nota.preview.edit'); // <-- baru
    Route::put('/nota/{nota}',           [NotaController::class, 'update'])->name('nota.update');   // <-- baru
    Route::delete('/nota/{nota}',        [NotaController::class, 'destroy'])->name('nota.destroy');
});