<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, ProfileController, AdminController, AbsenController,
    ForgotPasswordController, ResetPasswordController, BukuController,
    BookController, ArtikelController, RiwayatController, PeminjamanController,
    DendaController, AdminKartuController, DashboardController, ReviewController
};

// ============================
// GUEST ONLY (BELUM LOGIN)
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/signin', [AuthController::class, 'showSignInForm'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signinSubmit'])->name('signin.submit');
});

// ============================
// PUBLIC ROUTES (BISA DIAKSES SIAPA SAJA)
// ============================
Route::get('/', [AuthController::class, 'index'])->name('home');
Route::get('/home', [AuthController::class, 'index']);
Route::get('/about', fn () => view('auth.about'))->name('about');

// BUKU & ARTIKEL
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'kategori'])->name('buku.kategori');
Route::get('/buku/{id}', [BookController::class, 'showBook'])->name('buku.show');
Route::get('/artikel', [AuthController::class, 'artikel'])->name('auth.artikel');
Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');

// ✨ REVIEWS (PENTING: Di luar admin agar user bisa kirim)
Route::get('/kontak', [ReviewController::class, 'index'])->name('auth.review');
Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

// FORGOT PASSWORD
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ============================
// AUTH ROUTES (HARUS LOGIN)
// ============================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // HISTORY & NOTIF
    Route::get('/return-history', [AuthController::class, 'returnHistory'])->name('auth.return-history');
    Route::get('/borrow-history', [AuthController::class, 'borrowHistory'])->name('auth.borrow-history');
    Route::get('/fine-history', [AuthController::class, 'fineHistory'])->name('auth.fine-history');
    Route::get('/notifications', [AuthController::class, 'index'])->name('notifications');
    Route::post('/notifikasi/{id}/read', [AuthController::class, 'markAsRead']);

    // PINJAM BUKU
    Route::post('/pinjam/{book}', [PeminjamanController::class, 'store'])->name('pinjam.store');
    
    // CARD
    Route::get('/card/{id}', [AuthController::class, 'showCard'])->name('card');
    Route::get('/card/png/{id}', [AuthController::class, 'saveAsPNG'])->name('card.png');
});

// ============================
// ADMIN ROUTES (HANYA ADMIN)
// ============================
Route::prefix('admin')->middleware(['auth', 'check.admin'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // DATA USER
    Route::resource('datauser', AdminController::class, [
        'names' => 'admin.datauser',
        'parameters' => ['datauser' => 'id']
    ])->except(['show']); // Menggunakan resource agar lebih rapi

    // DATA ABSEN
    Route::get('/dataabsen', [AbsenController::class, 'index'])->name('admin.dataabsen');
    Route::post('/dataabsen', [AbsenController::class, 'store'])->name('admin.dataabsen.store');
    Route::put('/dataabsen/{id}', [AbsenController::class, 'update'])->name('admin.dataabsen.update');
    Route::delete('/dataabsen/{id}', [AbsenController::class, 'destroy'])->name('admin.dataabsen.delete');
    Route::get('/dataabsen/print', [AbsenController::class, 'printPdf'])->name('admin.dataabsen.print');
    Route::get('/dataabsen/export/{groupBy?}', [AbsenController::class, 'exportAbsens'])->name('admin.dataabsen.export');
    Route::get('/absen/scan', [AbsenController::class, 'scanPage'])->name('admin.absen.scan');
    Route::post('/absen/scan/store', [AbsenController::class, 'storeScan'])->name('admin.absen.scan.store');

    // DATA KOLEKSI (BUKU)
    Route::get('/datakoleksi', [BookController::class, 'index'])->name('admin.datakoleksi');
    Route::post('/datakoleksi', [BookController::class, 'store'])->name('admin.datakoleksi.store');
    Route::get('/datakoleksi/edit/{id}', [BookController::class, 'edit'])->name('admin.datakoleksi.edit'); 
    Route::put('/datakoleksi/{id}', [BookController::class, 'update'])->name('admin.datakoleksi.update');
    Route::delete('/datakoleksi/{id}', [BookController::class, 'destroy'])->name('admin.datakoleksi.delete');

    // DATA ARTIKEL
    Route::get('/dataartikel', [ArtikelController::class, 'index'])->name('admin.dataartikel');
    Route::post('/dataartikel', [ArtikelController::class, 'store'])->name('admin.dataartikel.store');
    Route::put('/dataartikel/{id}', [ArtikelController::class, 'update'])->name('admin.dataartikel.update');
    Route::delete('/dataartikel/{id}', [ArtikelController::class, 'destroy'])->name('admin.dataartikel.destroy');

    // RIWAYAT, PEMINJAMAN, DENDA
    Route::prefix('riwayat')->group(function () {
        Route::get('/peminjaman', [RiwayatController::class, 'indexPeminjaman'])->name('admin.riwayat.peminjaman.peminjaman');
        Route::post('/peminjaman/proses', [RiwayatController::class, 'prosesPeminjaman'])->name('admin.riwayat.peminjaman.proses');
        
        Route::prefix('pengembalian')->name('admin.riwayat.pengembalian.')->group(function () {
            Route::get('/', [RiwayatController::class, 'pengembalian'])->name('pengembalian');
            Route::post('/proses', [RiwayatController::class, 'prosesPengembalian'])->name('proses');
        });

        Route::resource('denda', DendaController::class, [
            'names' => 'admin.riwayat.denda'
        ]);
    });

    // KARTU ANGGOTA
    Route::get('/kartu', [AdminKartuController::class, 'index'])->name('admin.kartu');
    Route::post('/kartu/generate/{user}', [AdminKartuController::class, 'regenerate'])->name('admin.kartu.regenerate');
});