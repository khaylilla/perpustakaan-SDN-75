<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Umum;
use App\Models\Absen;
use App\Models\Book;
use App\Models\Artikel;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function showSignInForm()
{
    return view('auth.signin'); // ganti 'auth.signin' sesuai nama view sign-in kamu
}


public function index()
{
    return view('auth.home', [
        'user' => Auth::user(), // null kalau tamu
        'books' => Book::latest()->take(10)->get(),
        'bukuFavorit' => Peminjaman::selectRaw('judul_buku, COUNT(*) as total')
            ->groupBy('judul_buku')
            ->orderByDesc('total')
            ->take(5)
            ->get(),
        'artikels' => Artikel::latest()->take(5)->get(),
    ]);
}

    public function showCard($id)
    {
        $loginAs = session('login_as');
        $user = null;

        if ($loginAs === 'siswa') {
            $user = User::findOrFail($id);
        } elseif ($loginAs === 'guru') {
            $user = Guru::findOrFail($id);
        } elseif ($loginAs === 'umum') {
            $user = Umum::findOrFail($id);
        }

        return view('auth.card', compact('user', 'loginAs'));
    }
public function showAbsenForm()
{
    $userId = session('user_id');
    $user = User::find($userId); // kalau siswa
    return view('auth.absen', compact('user'));
}

public function submitAbsen(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'npm' => 'required|string|max:20',]);

    Absen::create([
        'user_id' => Auth::id(),
        'nama' => $request->nama,
        'npm' => $request->npm,
        'tanggal' => now()->toDateString(),
    ]);

    return back()->with('success', 'Absen berhasil disimpan untuk tanggal ' . now()->format('d-m-Y') . '!');
}

    // ======================
    // REGISTER (SUDAH OK)
    // ======================
    public function signinSubmit(Request $request)
    {
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto', 'public');
        }

        if ($request->role === 'siswa') {
            User::create([
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'asal_sekolah' => $request->asal_sekolah,
                'kelas' => $request->kelas,
                'foto' => $foto,
                'password' => Hash::make($request->password),
            ]);
        }

        elseif ($request->role === 'guru') {
            Guru::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'nip' => $request->nip,
                'alamat' => $request->alamat,
                'tgl_lahir' => $request->tgl_lahir,
                'nohp' => $request->nohp,
                'foto' => $foto,
                'password' => Hash::make($request->password),
            ]);
        }

        elseif ($request->role === 'umum') {
            Umum::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'tgl_lahir' => $request->tgl_lahir,
                'nohp' => $request->nohp,
                'foto' => $foto,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat');
    }

    // ======================
    // LOGIN (INI INTINYA)
    // ======================
public function loginSubmit(Request $request)
{
    // ======================
    // LOGIN ADMIN (HARDCODE)
    // ======================
    if (
        $request->identifier === 'admin123' &&
        $request->password === 'admin123'
    ) {
        session([
            'is_admin' => true,
            'login_as' => 'admin'
        ]);

        return redirect()->route('admin.dashboard');
    }

    $request->validate([
        'identifier' => 'required',
        'password'   => 'required',
    ]);

    $id = $request->identifier;
    $password = $request->password;

    // ======================
    // SISWA
    // ======================
    $siswa = User::where('nisn', $id)->first();
    if ($siswa && Hash::check($password, $siswa->password)) {
        Auth::login($siswa);
        $request->session()->regenerate();

        session([
            'login_as' => 'siswa',
            'user_id'  => $siswa->id,
        ]);

        return redirect('/home');
    }

    // ======================
    // GURU
    // ======================
    $guru = Guru::where('nip', $id)->first();
    if ($guru && Hash::check($password, $guru->password)) {
        Auth::login($guru);
        $request->session()->regenerate();

        session([
            'login_as' => 'guru',
            'guru_id'  => $guru->id,
        ]);

        return redirect('/home');
    }

    // ======================
    // UMUM
    // ======================
    $umum = Umum::where('email', $id)->first();
    if ($umum && Hash::check($password, $umum->password)) {
        Auth::login($umum);
        $request->session()->regenerate();

        session([
            'login_as' => 'umum',
            'umum_id'  => $umum->id,
        ]);

        return redirect('/home');
    }

    return back()->withErrors([
        'identifier' => 'Email / NISN / NIP atau password salah',
    ]);
}

    // ======================
    // LOGOUT
    // ======================
      public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}


// Halaman artikel untuk user
    public function artikel(Request $request)
    {
        // Fitur pencarian
        $query = Artikel::query();

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('judul', 'like', '%' . $request->keyword . '%')
                  ->orWhere('isi', 'like', '%' . $request->keyword . '%');
        }

        $artikels = $query->latest()->paginate(6); // tampil 6 per halaman

        return view('auth.artikel', compact('artikels'));
    }

public function returnHistory()
{
    $loginAs = session('login_as');
    $peminjaman = null;

    if ($loginAs === 'siswa') {
        $identifier = auth()->user()->nisn;
        $peminjaman = Peminjaman::where('status', 'dikembalikan')
                        ->where('nisn', $identifier)
                        ->orderBy('tanggal_kembali', 'desc')
                        ->get();
    } elseif ($loginAs === 'guru') {
        $identifier = auth()->user()->nip;
        $peminjaman = Peminjaman::where('status', 'dikembalikan')
                        ->where('nip', $identifier)
                        ->orderBy('tanggal_kembali', 'desc')
                        ->get();
    } elseif ($loginAs === 'umum') {
        $identifier = auth()->user()->email;
        $peminjaman = Peminjaman::where('status', 'dikembalikan')
                        ->where('email', $identifier)
                        ->orderBy('tanggal_kembali', 'desc')
                        ->get();
    }

    return view('auth.return-history', compact('peminjaman'));
}

public function borrowHistory()
{
    $loginAs = session('login_as');
    $peminjaman = null;

    if ($loginAs === 'siswa') {
        $identifier = auth()->user()->nisn;
        $peminjaman = Peminjaman::where('status', 'dipinjam')
                        ->where('nisn', $identifier)
                        ->orderBy('tanggal_pinjam', 'desc')
                        ->get();
    } elseif ($loginAs === 'guru') {
        $identifier = auth()->user()->nip;
        $peminjaman = Peminjaman::where('status', 'dipinjam')
                        ->where('nip', $identifier)
                        ->orderBy('tanggal_pinjam', 'desc')
                        ->get();
    } elseif ($loginAs === 'umum') {
        $identifier = auth()->user()->email;
        $peminjaman = Peminjaman::where('status', 'dipinjam')
                        ->where('email', $identifier)
                        ->orderBy('tanggal_pinjam', 'desc')
                        ->get();
    }

    return view('auth.borrow-history', compact('peminjaman'));
}

}
