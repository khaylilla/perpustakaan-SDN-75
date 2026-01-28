<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Umum;
use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Carbon\Carbon;
use PDF; // Pastikan package dompdf terinstall

class RiwayatController extends Controller
{
    // ============================
    // PEMINJAMAN SECTION
    // ============================

    // Halaman daftar peminjaman
    public function indexPeminjaman(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%")
                  ->orWhere('nomor_buku', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->paginate(10);
        return view('admin.riwayat.peminjaman.peminjaman', compact('peminjaman'));
    }

    // ✨ Method Scan Peminjaman (Disesuaikan dengan web.php)
    public function scanPeminjaman()
    {
        return view('admin.riwayat.peminjaman.scan');
    }

    // Export PDF peminjaman
    public function printPeminjamanPdf(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'dipinjam')->get();
        $pdf = PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'));
        return $pdf->download('laporan_peminjaman.pdf');
    }

    // ============================
    // PENGEMBALIAN SECTION
    // ============================

    // Halaman daftar pengembalian
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%")
                  ->orWhere('nomor_buku', 'like', "%{$keyword}%");
            });
        }

        // Default hanya menampilkan yang sudah dikembalikan
        $query->where('status', 'dikembalikan');

        $peminjaman = $query->orderBy('tanggal_kembali', 'desc')->paginate(10);
        return view('admin.riwayat.pengembalian.pengembalian', compact('peminjaman'));
    }

    // ✨ Method Scan Pengembalian (Disesuaikan dengan web.php)
    public function scanPengembalian()
    {
        return view('admin.riwayat.pengembalian.scankembali');
    }

    // ✨ Method PDF Pengembalian (Disesuaikan dengan web.php)
    public function printPengembalianPdf()
    {
        $peminjaman = Peminjaman::where('status', 'dikembalikan')->get();
        $pdf = PDF::loadView('admin.riwayat.pengembalian.pdfkembali', compact('peminjaman'));
        return $pdf->download('laporan_pengembalian.pdf');
    }

    // ✨ Method Hapus Pengembalian (Disesuaikan dengan web.php)
    public function destroyPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return redirect()->back()->with('success', 'Data riwayat pengembalian berhasil dihapus!');
    }

    // ============================
    // AJAX & LOGIC HELPERS
    // ============================

    public function getUser($kode)
    {
        $guru = Guru::where('nip', $kode)->first();
        if ($guru) return response()->json(['nama' => $guru->nama, 'peminjam_tipe' => 'guru']);

        $umum = Umum::where('email', $kode)->first();
        if ($umum) return response()->json(['nama' => $umum->nama, 'peminjam_tipe' => 'umum']);

        $siswa = User::where('nisn', $kode)->first();
        if ($siswa) return response()->json(['nama' => $siswa->nama, 'peminjam_tipe' => 'user']);

        return response()->json([]);
    }

    public function getBook($nomor)
    {
        $book = Book::where('nomor_buku', $nomor)->first();
        return response()->json($book ? ['judul' => $book->judul, 'jumlah' => $book->jumlah] : []);
    }

    public function prosesPeminjaman(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'nomor_buku' => 'required|exists:books,nomor_buku',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $identitas = $request->npm;
        $user = Guru::where('nip', $identitas)->first() 
                ?? Umum::where('email', $identitas)->first() 
                ?? User::where('nisn', $identitas)->first();

        if (!$user) return response()->json(['message' => 'User tidak ditemukan'], 404);

        $book = Book::where('nomor_buku', $request->nomor_buku)->first();
        $jumlah = $request->jumlah ?? 1;

        if ($book->jumlah < $jumlah) {
            return response()->json(['message' => 'Stok tidak mencukupi.'], 400);
        }

        Peminjaman::create([
            'nama' => $user->nama,
            'npm' => $identitas,
            'judul_buku' => $book->judul,
            'nomor_buku' => $book->nomor_buku,
            'jumlah' => $jumlah,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'dipinjam',
        ]);

        $book->decrement('jumlah', $jumlah);
        return response()->json(['message' => 'Peminjaman berhasil!']);
    }

    public function prosesPengembalian(Request $request)
    {
        $peminjaman = Peminjaman::where('npm', $request->npm)
            ->where('nomor_buku', $request->nomor_buku)
            ->where('status', 'dipinjam')
            ->first();

        if (!$peminjaman) return response()->json(['message' => 'Data tidak ditemukan.'], 400);

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()
        ]);

        Book::where('nomor_buku', $request->nomor_buku)->increment('jumlah', $peminjaman->jumlah);

        return response()->json(['message' => 'Buku berhasil dikembalikan!']);
    }
}