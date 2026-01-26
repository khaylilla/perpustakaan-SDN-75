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
    if (auth()->guard('guru')->check()) {
        $user = auth()->guard('guru')->user();
        $peminjamTipe = 'guru';
        $identitas = $user->nip;
        // Guru boleh pinjam multiple
    } elseif (auth()->guard('user')->check()) {
        $user = auth()->guard('user')->user();
        $peminjamTipe = 'user';
        $identitas = $user->npm;
        // User (siswa) dibatasi 1
        $jumlah = 1;
    } elseif (auth()->guard('umum')->check()) {
        $user = auth()->guard('umum')->user();
        $peminjamTipe = 'umum';
        $identitas = $user->npm;
        // Umum dibatasi 1
        $jumlah = 1;
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

    // ✨ SIMPAN PEMINJAMAN
    $tanggalPinjam = Carbon::now();
    $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

    Peminjaman::create([
        'nama' => $user->nama,
        'npm' => $identitas,
        'judul_buku' => $book->judul,
        'nomor_buku' => $book->nomor_buku,
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
