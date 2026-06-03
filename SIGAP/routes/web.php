<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\ProjectUpdateController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Konvensi penamaan route:
|   - Publik         : home, rekap, laporan.show
|   - Auth user      : lapor.create, lapor.store, laporan.destroy,
|                      laporan.riwayat, komentar.store, komentar.destroy,
|                      profile, profile.edit, profile.update, profile.password
|   - Admin          : admin.dashboard, admin.laporan.*, admin.status.update,
|                      admin.laporan.update.store, admin.statistik
|
*/

// ─────────────────────────────────────────────────────────────────────────────
// PUBLIC ROUTES
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Rekap / Semua Proyek
Route::get('/rekap', [ReportController::class, 'index'])
    ->name('rekap');

// Detail laporan (bisa diakses tamu)
Route::get('/laporan/{id}', [ReportController::class, 'show'])
    ->name('laporan.show');

// ─────────────────────────────────────────────────────────────────────────────
// AUTH ROUTES  (guest only – redirect ke home jika sudah login)
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');
});

// Logout (perlu auth)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED USER ROUTES
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (
        EmailVerificationRequest $request
    ) {
        $request->fulfill();

        return redirect('/profile');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (
        Request $request
    ) {
        $request->user()->sendEmailVerificationNotification();

        return back();
    })->middleware('throttle:6,1')
      ->name('verification.send');

    // ── LAPORAN ───────────────────────────────────────────────────────────────

    // Form buat laporan baru
    Route::get('/lapor', [ReportController::class, 'create'])
        ->name('lapor.create');

    // Submit laporan baru
    Route::post('/lapor', [ReportController::class, 'store'])
        ->name('lapor.store');

    // Hapus laporan sendiri (hanya boleh jika status = masuk)
    Route::delete('/laporan/{id}', [ReportController::class, 'destroy'])
        ->name('laporan.destroy');

    // Riwayat laporan milik user
    Route::get('/profile/riwayat', [ReportController::class, 'myReports'])
        ->name('laporan.riwayat');
    
    Route::get('/profile/riwayat/filter', [ReportController::class, 'filter'])
        ->name('laporan.filter');

    // ── KOMENTAR ──────────────────────────────────────────────────────────────

    // Tambah komentar pada laporan
    Route::post('/laporan/{id}/komentar', [CommentController::class, 'store'])
        ->name('komentar.store');

    // Hapus komentar sendiri
    Route::delete('/komentar/{id}', [CommentController::class, 'destroy'])
        ->name('komentar.destroy');

    // ── PROFIL ────────────────────────────────────────────────────────────────

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

});



// ─────────────────────────────────────────────────────────────────────────────
// ADMIN ROUTES  – middleware auth + admin (role check)
// ─────────────────────────────────────────────────────────────────────────────

Route::prefix('admin')
    ->middleware(['auth', 'admin'])   // 'admin' middleware: cek role === 'admin'
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // ── KELOLA LAPORAN ────────────────────────────────────────────────────

        Route::prefix('laporan')->name('laporan.')->group(function () {

            // Daftar semua laporan (termasuk ditolak)
            Route::get('/', [AdminController::class, 'laporanIndex'])
                ->name('index');

            // Detail laporan
            Route::get('/{id}', [AdminController::class, 'laporanShow'])
                ->name('show');

            // Update status (verifikasi / tolak / progress / selesai)
            Route::patch('/{id}/status', [AdminController::class, 'updateStatus'])
                ->name('status.update');

            // Hapus laporan (duplikat / spam)
            Route::delete('/{id}', [AdminController::class, 'laporanDestroy'])
                ->name('destroy');

            // Tambah update proyek
            Route::post('/{id}/update', [ProjectUpdateController::class, 'store'])
                ->name('update.store');
        });

        // ── STATISTIK (JSON endpoint untuk chart) ─────────────────────────────

        Route::get('/statistik', [AdminController::class, 'statistik'])
            ->name('statistik');
    });
