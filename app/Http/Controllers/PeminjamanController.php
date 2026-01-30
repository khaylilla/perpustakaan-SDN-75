<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\Guru;
use App\Models\Umum;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // index
public function index(Request $request)
{
    $query = Peminjaman::query();

    if($request->filter_type && $request->filter_date){
        if($request->filter_type == 'hari'){
            $query->whereDate('tanggal_pinjam', $request->filter_date);
        } elseif($request->filter_type == 'bulan'){
            $month = date('m', strtotime($request->filter_date));
            $year = date('Y', strtotime($request->filter_date));
            $query->whereMonth('tanggal_pinjam', $month)
                  ->whereYear('tanggal_pinjam', $year);
        } elseif($request->filter_type == 'tahun'){
            $year = date('Y', strtotime($request->filter_date));
            $query->whereYear('tanggal_pinjam', $year);
        }
    }

    $peminjaman = $query->get();
    return view('admin.riwayat.peminjaman.peminjaman', compact('peminjaman'));
}

// download PDF
public function downloadPDF(Request $request)
{
    $query = Peminjaman::query();

    if($request->filter_type && $request->filter_date){
        if($request->filter_type == 'hari'){
            $query->whereDate('tanggal_pinjam', $request->filter_date);
        } elseif($request->filter_type == 'bulan'){
            $month = date('m', strtotime($request->filter_date));
            $year = date('Y', strtotime($request->filter_date));
            $query->whereMonth('tanggal_pinjam', $month)
                  ->whereYear('tanggal_pinjam', $year);
        } elseif($request->filter_type == 'tahun'){
            $year = date('Y', strtotime($request->filter_date));
            $query->whereYear('tanggal_pinjam', $year);
        }
    }

    $peminjaman = $query->get();

    $pdf = \PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'));
    return $pdf->download('peminjaman.pdf');
}

// ✨ STORE PEMINJAMAN (User/Guru/Umum pinjam buku)
public function store(Request $request, Book $book)
{
    $request->validate([
        'jumlah' => 'required|integer|min:1|max:10'
    ]);

    $jumlah = $request->jumlah;
    $user = null;
    $loginAs = session('login_as');
    $nama = null;
    $nisn = null;
    $nip = null;
    $email = null;

    // ✨ CEK TIPE LOGIN (multi-auth)
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    $user = auth()->user();

    if ($loginAs === 'siswa') {
        $nama = $user->nama;
        $nisn = $user->nisn;
        $jumlah = 1; // Siswa dibatasi 1
    } elseif ($loginAs === 'guru') {
        $nama = $user->nama;
        $nip = $user->nip;
        // Guru boleh lebih dari 1 (misalnya sampai 5)
        $jumlah = min($request->jumlah, 5);
    } elseif ($loginAs === 'umum') {
        $nama = $user->nama;
        $email = $user->email;
        $jumlah = 1; // Umum dibatasi 1
    }

    // ✨ CEK STOK BUKU
    if ($book->jumlah < $jumlah) {
        return response()->json([
            'success' => false,
            'message' => 'Stok buku tidak mencukupi. Tersedia: ' . $book->jumlah
        ], 400);
    }

    // ✨ CEK BATASAN PEMINJAMAN
    if ($loginAs === 'siswa') {
        // Siswa hanya boleh 1 buku aktif
        $peminjamanAktif = Peminjaman::where('nisn', $nisn)
            ->where('status', 'dipinjam')
            ->count();

        if ($peminjamanAktif >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa hanya boleh meminjam 1 buku sekaligus. Kembalikan buku sebelumnya terlebih dahulu.'
            ], 400);
        }
    } elseif ($loginAs === 'guru') {
        // Guru boleh sampai 5 buku aktif
        $peminjamanAktif = Peminjaman::where('nip', $nip)
            ->where('status', 'dipinjam')
            ->count();

        if ($peminjamanAktif >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Guru hanya boleh meminjam maksimal 5 buku sekaligus.'
            ], 400);
        }
    } elseif ($loginAs === 'umum') {
        // Umum hanya boleh 1 buku aktif
        $peminjamanAktif = Peminjaman::where('email', $email)
            ->where('status', 'dipinjam')
            ->count();

        if ($peminjamanAktif >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Umum hanya boleh meminjam 1 buku sekaligus. Kembalikan buku sebelumnya terlebih dahulu.'
            ], 400);
        }
    }

    // ✨ SIMPAN PEMINJAMAN
    $tanggalPinjam = Carbon::now();
    $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

    Peminjaman::create([
        'nama' => $nama,
        'nisn' => $nisn,
        'nip' => $nip,
        'email' => $email,
        'judul_buku' => $book->judul,
        'nomor_buku' => $book->nomor_buku,
        'jumlah' => $jumlah,
        'tanggal_pinjam' => $tanggalPinjam,
        'tanggal_kembali' => $tanggalKembali,
        'status' => 'dipinjam',
    ]);

    // ✨ KURANGI STOK BUKU
    $book->decrement('jumlah', $jumlah);

    return response()->json([
        'success' => true,
        'message' => 'Buku berhasil dipinjam! Batas pengembalian: ' . $tanggalKembali->format('d/m/Y')
    ]);
}
}
