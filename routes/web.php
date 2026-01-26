<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminKartuController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// LOGIN (dengan middleware redirect jika sudah login)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

    // SIGN UP
    Route::get('/signin', [AuthController::class, 'showSignInForm'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signinSubmit'])->name('signin.submit');
});

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================
// HOME (TAMU & LOGIN)
// ============================
Route::get('/', [AuthController::class, 'index'])->name('home');
Route::get('/home', [AuthController::class, 'index']);


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/return-history', [AuthController::class, 'returnHistory'])->name('auth.return-history');
    Route::get('/borrow-history', [AuthController::class, 'borrowHistory'])->name('auth.borrow-history');
    Route::get('/fine-history', [AuthController::class, 'fineHistory'])->name('auth.fine-history');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ABOUT
Route::get('/about', function () {
    return view('auth.about');
})->name('about');


// ============================
// CARD
// ============================
Route::get('/card/{id}', [AuthController::class, 'showCard'])->name('card');
Route::get('/card/png/{id}', [AuthController::class, 'saveAsPNG'])->name('card.png');

// ============================
// DATA ABSEN CRUD
// ============================
Route::prefix('admin')->group(function() {

    // Halaman daftar absen
    Route::get('/data-absen', [AbsenController::class, 'index'])
        ->name('admin.dataabsen');

    // Tambah absen manual
    Route::post('/data-absen/store', [AbsenController::class, 'store'])
        ->name('admin.dataabsen.store');

    // Update absen
    Route::put('/data-absen/update/{id}', [AbsenController::class, 'update'])
        ->name('admin.dataabsen.update');

    // Hapus absen
    Route::delete('/data-absen/delete/{id}', [AbsenController::class, 'delete'])
        ->name('admin.dataabsen.delete');

    // Cetak PDF
    Route::get('/data-absen/print', [AbsenController::class, 'printPdf'])
        ->name('admin.dataabsen.print');
        // Export grouped absens
    Route::get('/data-absen/export/{groupBy?}', [AbsenController::class, 'exportAbsens'])
        ->name('admin.dataabsen.export');
    Route::get('/data-absen/print/full', [AbsenController::class, 'printFilteredPdf'])
        ->name('admin.dataabsen.print.full');

    // ============================
    // SCAN ABSEN
    // ============================
    Route::get('/absen/scan', [AbsenController::class, 'scanPage'])
        ->name('admin.absen.scan');

    Route::post('/absen/scan/store', [AbsenController::class, 'storeScan'])
        ->name('admin.absen.scan.store');

    Route::get('/absen/get-user/{npm}', [AbsenController::class, 'getUser'])
        ->name('admin.absen.get-user');

});
// ============================
// FORGOT & RESET PASSWORD
// ============================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ============================
// BUKU PUBLIC
// ============================
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Halaman daftar buku berdasarkan kategori
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'kategori'])->name('buku.kategori');

// Halaman detail buku
Route::get('/buku/{id}', [BookController::class, 'showBook'])->name('buku.show');

// ✨ ROUTE PEMINJAMAN BUKU (User/Guru/Umum)
Route::post('/pinjam/{book}', [PeminjamanController::class, 'store'])->middleware('auth');

Route::get('/artikel', [AuthController::class, 'artikel'])->name('auth.artikel');
Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');

Route::get('/kontak', function () {return view('auth.kontak');})->name('auth.kontak');

Route::get('/return-history', [AuthController::class, 'returnHistory'])->name('auth.return-history')->middleware('auth');
Route::get('/borrow-history', [AuthController::class, 'borrowHistory'])->name('auth.borrow-history')->middleware('auth');
Route::get('/fine-history', [AuthController::class, 'fineHistory'])->name('auth.fine-history')->middleware('auth');
Route::get('/notifications', [AuthController::class, 'index'])->name('notifications');
Route::post('/notifikasi/{id}/read', [AuthController::class, 'markAsRead']);


// ============================
// ADMIN ROUTES (middleware check.admin)
// ============================
Route::prefix('admin')->middleware('check.admin')->group(function () {
    
    // Dashboard
   Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->name('admin.dashboard');

    // DATA USER
    Route::get('/datauser', [AdminController::class, 'dataUser'])->name('admin.datauser');
    Route::get('/datauser/create', [AdminController::class, 'createUser'])->name('admin.datauser.create');
    Route::post('/datauser/store', [AdminController::class, 'storeUser'])->name('admin.datauser.store');
    Route::get('/datauser/edit/{id}', [AdminController::class, 'editUser'])->name('admin.datauser.edit');
    Route::put('/datauser/update/{id}', [AdminController::class, 'updateUser'])->name('admin.datauser.update');
    Route::delete('/datauser/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.datauser.delete');

    // DATA ABSEN
    Route::get('/dataabsen', [AbsenController::class, 'index'])->name('admin.dataabsen');
    Route::post('/dataabsen', [AbsenController::class, 'store'])->name('admin.dataabsen.store');
    Route::put('/dataabsen/{id}', [AbsenController::class, 'update'])->name('admin.dataabsen.update');
    Route::delete('/dataabsen/{id}', [AbsenController::class, 'destroy'])->name('admin.dataabsen.delete');
    Route::get('/dataabsen/print', [AbsenController::class, 'printPdf'])->name('admin.dataabsen.print');

    // DATA KOLEKSI
    Route::get('/datakoleksi', [BookController::class, 'index'])->name('admin.datakoleksi');
    Route::post('/datakoleksi', [BookController::class, 'store'])->name('admin.datakoleksi.store');
    Route::get('/datakoleksi/edit/{id}', [BookController::class, 'edit'])->name('admin.datakoleksi.edit'); 
    Route::put('/datakoleksi/{id}', [BookController::class, 'update'])->name('admin.datakoleksi.update');
    Route::delete('/datakoleksi/{id}', [BookController::class, 'destroy'])->name('admin.datakoleksi.delete');
    Route::get('/datakoleksi/pdf', [BookController::class, 'exportPDF'])->name('admin.datakoleksi.pdf');

    // DATA ARTIKEL
    Route::get('/dataartikel', [ArtikelController::class, 'index'])->name('admin.dataartikel');
    Route::post('/dataartikel', [ArtikelController::class, 'store'])->name('admin.dataartikel.store');
    Route::put('/dataartikel/{id}', [ArtikelController::class, 'update'])->name('admin.dataartikel.update');
    Route::delete('/dataartikel/{id}', [ArtikelController::class, 'destroy'])->name('admin.dataartikel.destroy');
    Route::get('/dataartikel/pdf', [ArtikelController::class, 'pdf'])->name('admin.dataartikel.pdf');

        // DATA NOTIFIKASI
    Route::get('/admin/notifikasi', [AdminController::class, 'notifikasi'])->name('admin.notifikasi');
    Route::post('/admin/notifikasi/store', [AdminController::class, 'notifikasiStore'])->name('admin.notifikasi.store');
    Route::put('/admin/notifikasi/update/{id}', [AdminController::class, 'notifikasiUpdate'])->name('admin.notifikasi.update');
    Route::delete('/admin/notifikasi/delete/{id}', [AdminController::class, 'notifikasiDelete'])->name('admin.notifikasi.delete');

  // ======== Manajemen Riwayat ========
    Route::prefix('riwayat')->group(function () {

    Route::get('/', [RiwayatController::class, 'indexPeminjaman'])->name('admin.riwayat.peminjaman.peminjaman');

    // ========== PEMINJAMAN ==========
    Route::prefix('peminjaman')->group(function () {
        Route::get('/peminjaman', [RiwayatController::class, 'indexPeminjaman'])->name('admin.riwayat.peminjaman.peminjaman');
        Route::get('/scan', [RiwayatController::class, 'scan'])->name('admin.riwayat.peminjaman.scan');
        Route::post('/proses', [RiwayatController::class, 'prosesPeminjaman'])->name('admin.riwayat.peminjaman.proses');

        // Tambahan baru 👇👇
        Route::put('/update/{id}', [RiwayatController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [RiwayatController::class, 'destroy'])->name('destroy');
        Route::get('/pdf', [RiwayatController::class, 'exportPdf'])->name('admin.riwayat.peminjaman.pdf');

        // Ajax
        Route::get('/get-user/{npm}', [RiwayatController::class,'getUser']);
        Route::get('/get-book/{nomor}', [RiwayatController::class,'getBook']);
    });

    // ========== PENGEMBALIAN & DENDA ==========

    // Pengembalian
    Route::prefix('riwayat/pengembalian')->name('admin.riwayat.pengembalian.')->group(function () {
        Route::get('/', [RiwayatController::class, 'pengembalian'])->name('pengembalian');
        Route::get('/scankembali', [RiwayatController::class, 'scanKembali'])->name('scankembali');
        Route::put('/update/{id}', [RiwayatController::class, 'updatePengembalian'])->name('update');
        Route::delete('/destroy/{id}', [RiwayatController::class, 'destroyPengembalian'])->name('destroy'); // <--- ini
        // Tambahkan ini untuk AJAX proses pengembalian
        Route::post('/proses', [RiwayatController::class, 'prosesPengembalian'])->name('proses');

        // PDF
        Route::get('/pdf', [RiwayatController::class, 'exportPdfPengembalian'])->name('pdfkembali');
    });

    // ========== DENDA ==========
    Route::prefix('denda')->name('admin.riwayat.denda.')->group(function () {
        Route::get('/', [DendaController::class, 'index'])->name('index');
        Route::post('/store', [DendaController::class, 'store'])->name('store');
        Route::put('/{id}/update', [DendaController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [DendaController::class, 'destroy'])->name('destroy');
        Route::get('/pdf', [DendaController::class, 'exportPdf'])->name('pdf');
    });
});

// ============================
// KARTU ANGGOTA
// ============================
// Halaman untuk lihat anggota dan generate ulang kartu
Route::get('/admin/kartu', [AdminKartuController::class, 'index'])->name('admin.kartu');
Route::get('/admin/kartu/generate', [AdminKartuController::class, 'index'])->name('admin.kartu.generate');

// Proses generate ulang kartu
Route::post('/admin/kartu/generate/{user}', [AdminKartuController::class, 'regenerate'])->name('admin.kartu.regenerate');

});