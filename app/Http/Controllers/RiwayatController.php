<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // Halaman scan peminjaman
    public function scan()
    {
        return view('admin.riwayat.peminjaman.scan');
    }

    // Halaman scan pengembalian
    public function scanKembali()
    {
        return view('admin.riwayat.pengembalian.scankembali');
    }

    // Ambil info user (ajax)
    public function getUser($npm)
    {
        $user = User::where('npm', $npm)->first();
        return response()->json($user ? ['nama' => $user->nama] : []);
    }

    // Ambil info buku (ajax peminjaman)
    public function getBook($nomor)
    {
        $book = Book::where('nomor_buku', $nomor)->first();
        return response()->json($book ? ['judul' => $book->judul, 'jumlah' => $book->jumlah] : []);
    }

    // Ambil info buku (ajax pengembalian)
    public function getBookForReturn($nomor, $npm)
    {
        $peminjaman = Peminjaman::where('nomor_buku', $nomor)
            ->where('npm', $npm)
            ->where('status', 'dipinjam')
            ->first();

        if (!$peminjaman) return response()->json([]);
        
        $book = Book::where('nomor_buku', $nomor)->first();
        return response()->json($book ? ['judul' => $book->judul, 'jumlah' => $book->jumlah] : []);
    }

    // Proses simpan peminjaman
    public function prosesPeminjaman(Request $request)
    {
        $request->validate([
            'npm' => 'required|exists:users,npm',
            'nomor_buku' => 'required|exists:books,nomor_buku',
        ]);

        $user = User::where('npm', $request->npm)->first();
        $book = Book::where('nomor_buku', $request->nomor_buku)->first();

        if ($book->jumlah <= 0) {
            return response()->json(['message' => 'Buku ini sedang habis/stok 0'], 400);
        }

        $tanggalPinjam = Carbon::now();
        $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

        Peminjaman::create([
            'nama' => $user->nama,
            'npm' => $user->npm,
            'judul_buku' => $book->judul,
            'nomor_buku' => $book->nomor_buku,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
        ]);

        $book->jumlah -= 1;
        $book->status = $book->jumlah <= 0 ? 'dipinjam' : 'tersedia';
        $book->save();

        return response()->json(['message' => 'Peminjaman berhasil diproses!']);
    }

    // Proses pengembalian
    public function prosesPengembalian(Request $request)
{
    $request->validate([
        'npm' => 'required',
        'nomor_buku' => 'required',
    ]);

    $npm = $request->npm;
    $nomorBuku = $request->nomor_buku;

    // Cari user
    $user = User::where('npm', $npm)->first();
    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan.'], 404);
    }

    // Cari buku
    $book = Book::where('nomor_buku', $nomorBuku)->first();
    if (!$book) {
        return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
    }

    // Tambah jumlah buku
    $book->jumlah += 1;

    // Update status jadi tersedia jika jumlah > 0
    if ($book->jumlah > 0) {
        $book->status = 'tersedia';
    }

    $book->save();

    // Update status peminjaman
    $peminjaman = Peminjaman::where('npm', $npm)
        ->where('nomor_buku', $nomorBuku)
        ->where('status', 'dipinjam')
        ->first();

    if ($peminjaman) {
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();
    }

    return response()->json(['message' => 'Buku berhasil dikembalikan.']);
}


    // Halaman manajemen riwayat (3 kotak)
    public function datariwayat()
    {
        return view('admin.datariwayat');
    }

    // Halaman daftar peminjaman
    public function indexPeminjaman()
    {
        $peminjaman = Peminjaman::all();
        return view('admin.riwayat.peminjaman.peminjaman', compact('peminjaman'));
    }

    // Halaman daftar pengembalian
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'dikembalikan');
        }

        $peminjaman = $query->orderBy('tanggal_kembali', 'desc')->get();
        return view('admin.riwayat.pengembalian.pengembalian', compact('peminjaman'));
    }

    // Update status pengembalian
    public function updatePengembalian(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'nomor_buku' => 'required',
            'status' => 'required|in:dipinjam,kembali',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'judul_buku' => $request->judul_buku,
            'nomor_buku' => $request->nomor_buku,
            'status' => $request->status,
            'tanggal_kembali' => $request->status === 'kembali' ? now() : null,
        ]);

        $book = Book::where('nomor_buku', $request->nomor_buku)->first();
        if ($request->status === 'kembali') {
            $book->jumlah += 1;
        } else {
            $book->jumlah -= 1;
        }
        $book->status = $book->jumlah > 0 ? 'tersedia' : 'dipinjam';
        $book->save();

        return redirect()->back()->with('success', 'Data pengembalian berhasil diupdate!');
    }

    // Hapus pengembalian
    public function destroyPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status === 'dipinjam') {
            $book = Book::where('nomor_buku', $peminjaman->nomor_buku)->first();
            $book->jumlah += 1;
            $book->status = 'tersedia';
            $book->save();
        }

        $peminjaman->delete();
        return redirect()->back()->with('success', 'Data pengembalian berhasil dihapus!');
    }

    // Hapus data peminjaman
    public function destroyPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return redirect()->route('admin.riwayat.peminjaman.peminjaman')->with('success', 'Data peminjaman berhasil dihapus!');
    }

    // Update data peminjaman
    public function updatePeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'judul_buku' => $request->judul_buku,
            'nomor_buku' => $request->nomor_buku,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.riwayat.peminjaman.peminjaman')->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    // Halaman denda
    public function denda()
    {
        return view('admin.riwayat.denda');
    }

    // Export PDF peminjaman
    public function exportPdf(Request $request)
    {
        $peminjaman = Peminjaman::all();
        $pdf = \PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('data_peminjaman.pdf');
    }

    // Export PDF pengembalian
    public function exportPdfPengembalian(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'kembali')->get();
        $pdf = \PDF::loadView('admin.riwayat.pengembalian.pdfkembali', compact('peminjaman'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('data_pengembalian.pdf');
    }
}
