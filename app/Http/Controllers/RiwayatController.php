<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Umum;
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

    // Ambil info user (ajax) - cek Guru & Umum & User
    public function getUser($kode)
    {
        // Cek tabel guru berdasarkan NIP
        $guru = Guru::where('nip', $kode)->first();
        if ($guru) {
            return response()->json([
                'nama' => $guru->nama,
                'peminjam_tipe' => 'guru'
            ]);
        }

        // Cek tabel umum berdasarkan email
        $umum = Umum::where('email', $kode)->first();

        if ($umum) {
            return response()->json([
                'nama' => $umum->nama,
                'peminjam_tipe' => 'umum'
            ]);
        }

        // Cek tabel user (siswa) berdasarkan NISN
        $siswa = User::where('nisn', $kode)->first();
        if ($siswa) {
            return response()->json([
                'nama' => $siswa->nama,
                'peminjam_tipe' => 'user'
            ]);
        }

        return response()->json([]);
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
            'npm' => 'required',
            'nomor_buku' => 'required|exists:books,nomor_buku',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $jumlah = $request->jumlah ?? 1;
        $identitas = $request->npm;

        // ✨ CEK TABEL GURU berdasarkan NIP
        $guru = Guru::where('nip', $identitas)->first();
        if ($guru) {
            $user = $guru;
            $peminjamTipe = 'guru';
        } else {
            // CEK TABEL UMUM berdasarkan email
            $umum = Umum::where('email', $identitas)->first();

            if ($umum) {
                $user = $umum;
                $peminjamTipe = 'umum';
            } else {
                // CEK TABEL USER (siswa) berdasarkan NISN
                $siswa = User::where('nisn', $identitas)->first();
                if ($siswa) {
                    $user = $siswa;
                    $peminjamTipe = 'user';
                }
            }
        }

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $book = Book::where('nomor_buku', $request->nomor_buku)->first();

        if (!$book || $book->jumlah < $jumlah) {
            return response()->json(['message' => 'Buku ini sedang habis/stok tidak mencukupi. Stok tersedia: ' . ($book->jumlah ?? 0)], 400);
        }

        // ✨ BATASAN PEMINJAMAN: Guru boleh unlimited, Siswa & Umum hanya 1
        if ($peminjamTipe !== 'guru') {
            // Siswa & Umum dibatasi 1 buku total (bukan 1 jumlah)
            $kodeIdentitas = $peminjamTipe === 'user' ? $user->nisn : $user->email;
            
            $jumlahPinjam = Peminjaman::where('npm', $kodeIdentitas)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjam >= 1) {
                return response()->json([
                    'message' => 'Peminjaman gagal. ' . ucfirst($peminjamTipe) . ' hanya boleh meminjam 1 buku sekaligus.'
                ], 400);
            }
            
            // Force jumlah = 1 untuk non-guru
            $jumlah = 1;
        }

        $tanggalPinjam = Carbon::now();
        $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

        // Simpan ke peminjaman - Guru pakai NIP, Umum pakai email, Siswa pakai NISN
        if ($peminjamTipe === 'guru') {
            $kodeIdentitas = $user->nip;
        } elseif ($peminjamTipe === 'umum') {
            $kodeIdentitas = $user->email;
        } else {
            $kodeIdentitas = $user->nisn;
        }

        // Buat 1 record peminjaman dengan jumlah
        Peminjaman::create([
            'nama' => $user->nama,
            'npm' => $kodeIdentitas,
            'judul_buku' => $book->judul,
            'nomor_buku' => $book->nomor_buku,
            'jumlah' => $jumlah,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
        ]);

        // Kurangi stok buku
        $book->decrement('jumlah', $jumlah);
        $book->status = $book->jumlah <= 0 ? 'dipinjam' : 'tersedia';
        $book->save();

        return response()->json(['message' => 'Peminjaman berhasil diproses! ' . $jumlah . ' buku dipinjam sampai ' . $tanggalKembali->format('d/m/Y')]);
    }

    // Proses pengembalian
    public function prosesPengembalian(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'nomor_buku' => 'required',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $npm = $request->npm;
        $nomorBuku = $request->nomor_buku;
        $jumlah = $request->jumlah ?? 1;

        // Cek book exists
        $book = Book::where('nomor_buku', $nomorBuku)->first();
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        // Cari peminjaman yang aktif (dipinjam) untuk npm ini
        $peminjaman = Peminjaman::where('npm', $npm)
            ->where('nomor_buku', $nomorBuku)
            ->where('status', 'dipinjam')
            ->first();

        if (!$peminjaman) {
            return response()->json(['message' => 'Buku ini tidak sedang dipinjam oleh anggota ini.'], 400);
        }

        // ✨ BATASAN PENGEMBALIAN: Siswa & Umum hanya boleh mengembalikan 1 buku
        // Cek tipe peminjam berdasarkan npm (email untuk umum, nisn untuk siswa, nip untuk guru)
        $guru = Guru::where('nip', $npm)->first();
        $umum = Umum::where('email', $npm)->first();
        $siswa = User::where('nisn', $npm)->first();

        if (($umum || $siswa) && $jumlah > 1) {
            return response()->json([
                'message' => 'Pengembalian gagal. Siswa dan Umum hanya boleh mengembalikan maksimal 1 buku.'
            ], 400);
        }

        // Update peminjaman status menjadi dikembalikan dan set jumlah_kembali
        $peminjaman->status = 'dikembalikan';
        $peminjaman->jumlah_kembali = $jumlah;
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();

        // Increment stock buku sesuai jumlah yang dikembalikan
        $book->increment('jumlah', $jumlah);
        $book->status = $book->jumlah > 0 ? 'tersedia' : 'dipinjam';
        $book->save();

        return response()->json(['message' => 'Pengembalian berhasil diproses! ' . $jumlah . ' buku dikembalikan.']);
    }

    // Halaman daftar peminjaman
    public function indexPeminjaman(Request $request)
    {
        $query = Peminjaman::query();

        // SEARCH
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%")
                  ->orWhere('nomor_buku', 'like', "%{$keyword}%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        return view('admin.riwayat.peminjaman.peminjaman', compact('peminjaman'));
    }

    // Halaman daftar pengembalian
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::query();

        // SEARCH
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%")
                  ->orWhere('nomor_buku', 'like', "%{$keyword}%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
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

    // Export PDF peminjaman
    public function exportPdf(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'Dipinjam')->get();
        $pdf = \PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'));
        return $pdf->download('peminjaman_dipinjam.pdf');
    }
}