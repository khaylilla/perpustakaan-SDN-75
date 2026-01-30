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
    $peminjamTipe = null;
    $identitas = null;

    // ✨ CEK TIPE LOGIN (multi-auth)
    if (auth()->check()) {
        $user = auth()->user();
        $loginAs = session('login_as');
        
        if ($loginAs === 'siswa') {
            $peminjamTipe = 'siswa';
            $identitas = $user->nisn;
            $jumlah = 1; // Siswa dibatasi 1
        } elseif ($loginAs === 'guru') {
            $peminjamTipe = 'guru';
            $identitas = $user->nip;
        } elseif ($loginAs === 'umum') {
            $peminjamTipe = 'umum';
            $identitas = $user->email;
            $jumlah = 1; // Umum dibatasi 1
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tipe pengguna tidak dikenali'
            ], 400);
        }
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    // ✨ CEK STOK BUKU
    if ($book->jumlah < $jumlah) {
        return response()->json([
            'success' => false,
            'message' => 'Stok buku tidak mencukupi. Tersedia: ' . $book->jumlah
        ], 400);
    }

    // ✨ CEK BATASAN PEMINJAMAN (Siswa/Umum hanya boleh 1)
    if ($peminjamTipe !== 'guru') {
        $peminjamanAktif = Peminjaman::where('npm', $identitas)
            ->where('status', 'dipinjam')
            ->count();

        if ($peminjamanAktif >= 1) {
            return response()->json([
                'success' => false,
                'message' => ucfirst($peminjamTipe) . ' hanya boleh meminjam 1 buku sekaligus. Kembalikan buku sebelumnya terlebih dahulu.'
            ], 400);
        }
    }

    // ✨ DURASI BERDASARKAN ROLE
    $tanggalPinjam = Carbon::now();
    $hari = ($peminjamTipe === 'guru') ? 14 : 7;
    $tanggalKembali = $tanggalPinjam->copy()->addDays($hari);

    // ✨ SIMPAN PEMINJAMAN DENGAN ROLE
    Peminjaman::create([
        'nama' => $user->nama,
        'npm' => $identitas,
        'judul_buku' => $book->judul,
        'nomor_buku' => $book->nomor_buku,
        'jumlah' => $jumlah,
        'tanggal_pinjam' => $tanggalPinjam,
        'tanggal_kembali' => $tanggalKembali,
        'status' => 'dipinjam',
        'role' => $peminjamTipe,
    ]);

    // ✨ KURANGI STOK BUKU
    $book->decrement('jumlah', $jumlah);

    return response()->json([
        'success' => true,
        'message' => 'Buku berhasil dipinjam! Batas pengembalian: ' . $tanggalKembali->format('d/m/Y')
    ]);
}

    /**
     * Show peminjaman for the currently authenticated user, filtered by role.
     */
    public function myPeminjaman()
    {
        $loginAs = session('login_as');

        if ($loginAs === 'siswa') {
            $identifier = auth()->user()->nisn;
            $role = 'siswa';
        } elseif ($loginAs === 'guru') {
            $identifier = auth()->user()->nip;
            $role = 'guru';
        } elseif ($loginAs === 'umum') {
            $identifier = auth()->user()->email;
            $role = 'umum';
        } else {
            abort(403);
        }

        $peminjaman = Peminjaman::where('role', $role)
            ->where('npm', $identifier)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('auth.peminjaman', compact('peminjaman'));
    }
}
