<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absen;
use App\Models\Book;
use App\Models\Artikel;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ✅ Menampilkan halaman signin
    public function showSignInForm()
    {
        return view('auth.signin');
    }

    // ✅ Menangani submit form signin
  public function signinSubmit(Request $request)
{
    // pastikan gak ada session login aktif
    Auth::logout();

    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'nama' => 'required',
        'npm' => 'required',
        'alamat' => 'required',
        'tgl_lahir' => 'required|date',
        'nohp' => 'required',
        'foto' => 'required|image|max:2048',
    ]);

    $fotoPath = $request->file('foto')->store('public/foto');

    $user = User::create([
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'nama' => $request->nama,
        'npm' => $request->npm,
        'alamat' => $request->alamat,
        'tgl_lahir' => $request->tgl_lahir,
        'nohp' => $request->nohp,
        'foto' => basename($fotoPath),
    ]);

    return redirect()
        ->route('signin')
        ->with([
            'success' => 'Anda berhasil melakukan pendaftaran akun!',
            'user_id' => $user->id,
        ]);
}

    // ✅ Menampilkan kartu anggota
    public function showCard($id)
    {
        $user = User::findOrFail($id);
        return view('auth.card', compact('user'));
    }
    
    // ✅ Menampilkan halaman absen
public function showAbsenForm()
{
    $user = Auth::user();
    return view('auth.absen', compact('user'));
}

// ✅ Menyimpan data absensi
public function submitAbsen(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'npm' => 'required|string|max:20',
        'prodi' => 'required|string|max:100',
    ]);

    Absen::create([
        'user_id' => Auth::id(),
        'nama' => $request->nama,
        'npm' => $request->npm,
        'prodi' => $request->prodi,
        'tanggal' => now()->toDateString(),
    ]);

    return back()->with('success', 'Absen berhasil disimpan untuk tanggal ' . now()->format('d-m-Y') . '!');
}

    // ✅ Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Menangani submit login
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'password' => 'required',
        ]);

        // Coba autentikasi berdasarkan NPM dan password
        if (Auth::attempt([
            'npm' => $request->npm,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('home'); // arahkan ke dashboard / halaman utama
        }

        return back()->withErrors([
            'npm' => 'NPM atau password salah.',
        ])->onlyInput('npm');
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

      public function show($id)
{
    $book = Book::findOrFail($id); // ambil buku berdasarkan ID
    return view('auth.show', compact('book'));
}

// Halaman artikel untuk user
    public function artikel(Request $request)
    {
        // Fitur pencarian
        $query = Artikel::query();

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('judul', 'like', '%' . $request->keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->keyword . '%');
        }

        $artikels = $query->latest()->paginate(6); // tampil 6 per halaman

        return view('auth.artikel', compact('artikels'));
    }

}

