<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriPelatihanController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════
// GUEST — hanya bisa diakses kalau belum login
// ═══════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/',         fn() => redirect()->route('login'));
    Route::get('/login',    [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register'])->name('register.post');

    // ─── Lupa Kata Sandi / Reset Password ───
    Route::get('/forgot-password',  [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ═══════════════════════════════════════════════════
// AUTH — harus login
// ═══════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (role-aware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Sertifikat — semua role bisa akses ───
    Route::get('/sertifikat/milik',        [SertifikatController::class, 'milik'])->name('sertifikat.milik');
    Route::get('/sertifikat/{sertifikat}', [SertifikatController::class, 'show'])->name('sertifikat.show');

    // ─── Pelatihan — read: semua role ───
    Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');

    // PENTING: /create harus didaftarkan SEBELUM /{pelatihan}
    Route::get('/pelatihan/create', [PelatihanController::class, 'create'])
        ->middleware('role:admin,trainer')
        ->name('pelatihan.create');

    Route::get('/pelatihan/{pelatihan}', [PelatihanController::class, 'show'])->name('pelatihan.show');

    // ─── Pelatihan — write: admin & trainer ───
    Route::middleware('role:admin,trainer')->group(function () {
        Route::post('/pelatihan',                 [PelatihanController::class, 'store'])->name('pelatihan.store');
        Route::get('/pelatihan/{pelatihan}/edit', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
        Route::put('/pelatihan/{pelatihan}',      [PelatihanController::class, 'update'])->name('pelatihan.update');
    });

    // ─── Pelatihan — hapus & ubah status: admin only ───
    Route::middleware('role:admin')->group(function () {
        Route::delete('/pelatihan/{pelatihan}',        [PelatihanController::class, 'destroy'])->name('pelatihan.destroy');
        Route::patch('/pelatihan/{pelatihan}/status',  [PelatihanController::class, 'updateStatus'])->name('pelatihan.updateStatus');
    });

    // ─────────────────────────────────────────
    // MATERI — nested di bawah pelatihan
    // ─────────────────────────────────────────
    Route::prefix('pelatihan/{pelatihan}/materi')->name('pelatihan.materi.')->group(function () {

        Route::get('/', [MateriController::class, 'index'])->name('index');

        // Statis — harus di atas /{materi}
        Route::middleware('role:admin,trainer')->group(function () {
            Route::get('/create',        [MateriController::class, 'create'])->name('create');
            Route::post('/',             [MateriController::class, 'store'])->name('store');
            Route::get('/{materi}/edit', [MateriController::class, 'edit'])->name('edit');
            Route::put('/{materi}',      [MateriController::class, 'update'])->name('update');
            Route::delete('/{materi}',   [MateriController::class, 'destroy'])->name('destroy');
        });

        // Dinamis — harus di bawah /create
        Route::get('/{materi}', [MateriController::class, 'show'])->name('show');
        Route::post('/{materi}/selesaikan', [MateriController::class, 'selesaikan'])
            ->middleware('role:karyawan')
            ->name('selesaikan');
    });

    // ─────────────────────────────────────────
    // ABSENSI
    // ─────────────────────────────────────────
    Route::prefix('pelatihan/{pelatihan}/absensi')->name('pelatihan.absensi.')->group(function () {
        Route::get('/rekap', [AbsensiController::class, 'rekap'])->name('rekap');
        Route::get('/',      [AbsensiController::class, 'index'])->name('index');

        Route::middleware('role:admin,trainer')->group(function () {
            Route::post('/', [AbsensiController::class, 'store'])->name('store');
        });
    });

    // ─────────────────────────────────────────
    // PENILAIAN
    // ─────────────────────────────────────────
    Route::prefix('pelatihan/{pelatihan}/penilaian')->name('pelatihan.penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');

        Route::middleware('role:admin,trainer')->group(function () {
            Route::post('/bulk-update',      [PenilaianController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/{penilaian}/edit',  [PenilaianController::class, 'edit'])->name('edit');
            Route::put('/{penilaian}',       [PenilaianController::class, 'update'])->name('update');
        });
    });

    // ─────────────────────────────────────────
    // SERTIFIKAT per pelatihan (admin only)
    // ─────────────────────────────────────────
    Route::prefix('pelatihan/{pelatihan}/sertifikat')->name('pelatihan.sertifikat.')->middleware('role:admin')->group(function () {
        Route::get('/',                    [SertifikatController::class, 'index'])->name('index');
        Route::post('/generate',           [SertifikatController::class, 'generate'])->name('generate');
        Route::delete('/{sertifikat}',     [SertifikatController::class, 'destroy'])->name('destroy');
    });

    // ─────────────────────────────────────────
    // PENDAFTARAN
    // ─────────────────────────────────────────
    Route::post('/pelatihan/{pelatihan}/daftar',     [PendaftaranController::class, 'daftar'])
        ->middleware('role:karyawan')->name('pendaftaran.daftar');
    Route::delete('/pelatihan/{pelatihan}/batalkan', [PendaftaranController::class, 'batalkan'])
        ->middleware('role:karyawan')->name('pendaftaran.batalkan');

    Route::middleware('role:admin,trainer')->group(function () {
        Route::get('/pendaftaran',                        [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::post('/pendaftaran/bulk-proses',           [PendaftaranController::class, 'bulkProses'])->name('pendaftaran.bulk-proses');
        Route::patch('/pendaftaran/{pendaftaran}/proses', [PendaftaranController::class, 'proses'])->name('pendaftaran.proses');
    });

    // ─────────────────────────────────────────
    // ADMIN ONLY
    // ─────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::resource('users',    UserController::class);
        Route::resource('kategori', KategoriPelatihanController::class)->except(['show']);
    });
});