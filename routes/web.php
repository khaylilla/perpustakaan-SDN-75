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
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ============================
// FORM SIGN UP
// ============================
Route::get('/signin', [AuthController::class, 'showSignInForm'])->name('signin');
Route::post('/signin', [AuthController::class, 'signinSubmit'])->name('signin.submit');

// ============================
// LOGIN
// ============================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'npm' => 'required',
        'password' => 'required',
    ]);

    $npm = $request->npm;
    $password = $request->password;

    // Login admin hardcoded
    if ($npm === 'admin123' && $password === 'admin123') {
        $request->session()->put('is_admin', true);
        return redirect()->route('admin.dashboard');
    }

    // Login user biasa
    $user = User::where('npm', $npm)->first();
    if ($user && Hash::check($password, $user->password)) {
        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('is_admin');
        return redirect()->route('home');
    }

    return back()->withErrors([
        'npm' => 'NPM atau password salah.',
    ])->onlyInput('npm');
})->name('login.submit');

// ============================
// LOGOUT
// ============================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::post('/admin/logout', function (Request $request) {
    $request->session()->forget('is_admin');
    return redirect()->route('login');
})->name('admin.logout');

// ============================
// ROOT REDIRECT
// ============================
Route::get('/', fn() => redirect()->route('login'));

// ============================
// HOME & PROFILE
// ============================
Route::get('/home', function () {
    return view('auth.home');
})->middleware('auth')->name('home');

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::get('/about', function () {
    return view('auth.about');
})->middleware('auth')->name('about');

// ============================
// CARD
// ============================
Route::get('/card/{id}', [AuthController::class, 'showCard'])->name('card');
Route::get('/card/png/{id}', [AuthController::class, 'saveAsPNG'])->name('card.png');

// ============================
// ABSEN
// ============================
Route::get('/absen', [AuthController::class, 'showAbsenForm'])->name('absen');
Route::post('/absen', [AuthController::class, 'submitAbsen'])->name('absen.submit');

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
Route::get('/buku/{id}', [AuthController::class, 'show'])->name('buku.show');

Route::get('/artikel', [AuthController::class, 'artikel'])->name('auth.artikel');

Route::get('/kontak', function () {return view('auth.kontak');})->name('auth.kontak');

// ============================
// ADMIN ROUTES (middleware check.admin)
// ============================
Route::prefix('admin')->middleware('check.admin')->group(function () {
    
    // Dashboard
    Route::get('/', function() {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // DATA USER
    Route::get('/datauser', [AdminController::class, 'dataUser'])->name('admin.datauser');
    Route::get('/datauser/create', [AdminController::class, 'createUser'])->name('admin.datauser.create');
    Route::post('/datauser/store', [AdminController::class, 'storeUser'])->name('admin.datauser.store');
    Route::get('/datauser/edit/{id}', [AdminController::class, 'editUser'])->name('admin.datauser.edit');
    Route::post('/datauser/update/{id}', [AdminController::class, 'updateUser'])->name('admin.datauser.update');
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

  // ======== Manajemen Riwayat ========
    Route::prefix('riwayat')->group(function () {

    Route::get('/', [RiwayatController::class, 'datariwayat'])->name('admin.datariwayat');

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

    Route::get('/denda', [RiwayatController::class, 'denda'])->name('admin.riwayat.denda');
});
});


