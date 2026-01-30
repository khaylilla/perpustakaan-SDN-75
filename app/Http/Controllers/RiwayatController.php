<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Umum;
use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Carbon\Carbon;
use PDF;

class RiwayatController extends Controller
{
    // ============================
    // PEMINJAMAN SECTION
    // ============================

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

    public function scanPeminjaman()
    {
        return view('admin.riwayat.peminjaman.scan');
    }

    public function printPeminjamanPdf(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'dipinjam')->get();
        $pdf = PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'));
        return $pdf->download('laporan_peminjaman.pdf');
    }

    /**
     * Update data peminjaman (Digunakan oleh tombol Edit di Blade)
     */
    public function updatePeminjaman(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'status' => 'required'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        
        // Logika jika status diubah ke dikembalikan via manual edit
        if ($peminjaman->status == 'dipinjam' && $request->status == 'dikembalikan') {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()
            ]);
            Book::where('nomor_buku', $peminjaman->nomor_buku)->increment('jumlah', $peminjaman->jumlah ?? 1);
        } else {
            $peminjaman->update($request->all());
        }

        return redirect()->back()->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    /**
     * Hapus data peminjaman (Digunakan oleh tombol Trash di Blade)
     */
    public function destroyPeminjaman($id)
    {
        $p = Peminjaman::findOrFail($id);
        
        // Kembalikan stok jika dihapus saat masih dipinjam
        if ($p->status == 'dipinjam') {
            Book::where('nomor_buku', $p->nomor_buku)->increment('jumlah', $p->jumlah ?? 1);
        }

        $p->delete();
        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus!');
    }

    // ============================
    // PENGEMBALIAN SECTION
    // ============================

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

        $query->where('status', 'dikembalikan');

        $peminjaman = $query->orderBy('tanggal_kembali', 'desc')->paginate(10);
        return view('admin.riwayat.pengembalian.pengembalian', compact('peminjaman'));
    }

    public function scanPengembalian()
    {
        return view('admin.riwayat.pengembalian.scankembali');
    }

    public function printPengembalianPdf()
    {
        $peminjaman = Peminjaman::where('status', 'dikembalikan')->get();
        $pdf = PDF::loadView('admin.riwayat.pengembalian.pdfkembali', compact('peminjaman'));
        return $pdf->download('laporan_pengembalian.pdf');
    }

    /**
     * Update data pengembalian (Fungsi yang tadi hilang)
     */
    public function updatePengembalian(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'jumlah_kembali' => 'required|integer|min:0',
            'status' => 'required'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        
        // Logika jika status dikembalikan ke 'dipinjam' (Pembatalan Pengembalian)
        if ($peminjaman->status == 'dikembalikan' && $request->status == 'dipinjam') {
            // Kurangi stok buku kembali karena statusnya jadi dipinjam lagi
            Book::where('nomor_buku', $peminjaman->nomor_buku)->decrement('jumlah', $peminjaman->jumlah ?? 1);
            
            $peminjaman->update([
                'nama' => $request->nama,
                'npm' => $request->npm,
                'jumlah' => $request->jumlah_kembali, // update jumlah jika ada perubahan
                'status' => 'dipinjam',
                'tanggal_kembali' => null // hapus tanggal kembali
            ]);
        } else {
            // Update biasa jika status tetap 'dikembalikan'
            $peminjaman->update([
                'nama' => $request->nama,
                'npm' => $request->npm,
                'jumlah' => $request->jumlah_kembali,
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with('success', 'Data pengembalian berhasil diperbarui!');
    }

    public function destroyPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return redirect()->back()->with('success', 'Data riwayat pengembalian berhasil dihapus!');
    }

    // ============================
    // AJAX & LOGIC HELPERS (SCANNER API)
    // ============================
    public function getUser($id)
    {
        $kode = trim($id);

        $guru = Guru::where('nip', $kode)->first();
        if ($guru) return response()->json(['nama' => $guru->nama, 'peminjam_tipe' => 'guru']);

        $umum = Umum::where('email', $kode)->orWhere('nohp', $kode)->first();
        if ($umum) return response()->json(['nama' => $umum->nama, 'peminjam_tipe' => 'umum']);

        $siswa = User::where('nisn', $kode)->first();
        if ($siswa) return response()->json(['nama' => $siswa->nama, 'peminjam_tipe' => 'user']);

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function getBook($id)
    {
        $nomor = trim($id);
        $book = Book::where('nomor_buku', $nomor)->first();
        
        if ($book) {
            return response()->json([
                'judul' => $book->judul, 
                'jumlah' => $book->jumlah
            ]);
        }
        
        return response()->json(['message' => 'Buku tidak ditemukan'], 404);
    }

    public function prosesPeminjaman(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'nomor_buku' => 'required|exists:books,nomor_buku',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $identitas = trim($request->npm);
        
        $user = Guru::where('nip', $identitas)->first() 
                ?? Umum::where('email', $identitas)->orWhere('nohp', $identitas)->first() 
                ?? User::where('nisn', $identitas)->first();

        if (!$user) return response()->json(['message' => 'User tidak ditemukan'], 404);

        $book = Book::where('nomor_buku', $request->nomor_buku)->first();
        $jumlah = $request->jumlah ?? 1;

        if ($book->jumlah < $jumlah) {
            return response()->json(['message' => 'Stok tidak mencukupi.'], 400);
        }

        $tipe = (isset($user->nip)) ? 'guru' : 'umum/siswa';
        $hari = ($tipe == 'guru') ? 14 : 7;
        
        $nisn = null;
        $nip = null;
        $email = null;

        if (isset($user->nip)) {
            $nip = $user->nip;
        } elseif (isset($user->email) && !isset($user->nisn)) {
            $email = $user->email;
        } elseif (isset($user->nisn)) {
            $nisn = $user->nisn;
        }

        Peminjaman::create([
            'nama' => $user->nama,
            'nisn' => $nisn,
            'nip' => $nip,
            'email' => $email,
            'judul_buku' => $book->judul,
            'nomor_buku' => $book->nomor_buku,
            'jumlah' => $jumlah,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays($hari),
            'status' => 'dipinjam',
        ]);

        $book->decrement('jumlah', $jumlah);
        return response()->json(['message' => 'Peminjaman berhasil dicatat!']);
    }

    public function prosesPengembalian(Request $request)
    {
        $identitas = trim($request->npm);
        
        // Cari user dulu untuk tentukan kolom mana yang dipakai
        $user = Guru::where('nip', $identitas)->first() 
                ?? Umum::where('email', $identitas)->orWhere('nohp', $identitas)->first() 
                ?? User::where('nisn', $identitas)->first();

        if (!$user) return response()->json(['message' => 'User tidak ditemukan'], 404);

        $nisn = null;
        $nip = null;
        $email = null;

        if (isset($user->nip)) {
            $nip = $user->nip;
        } elseif (isset($user->email) && !isset($user->nisn)) {
            $email = $user->email;
        } elseif (isset($user->nisn)) {
            $nisn = $user->nisn;
        }

        // Cari peminjaman dengan kolom yang sesuai
        $query = Peminjaman::where('nomor_buku', $request->nomor_buku)
            ->where('status', 'dipinjam');

        if ($nip) {
            $query->where('nip', $nip);
        } elseif ($email) {
            $query->where('email', $email);
        } else {
            $query->where('nisn', $nisn);
        }

        $peminjaman = $query->first();

        if (!$peminjaman) return response()->json(['message' => 'Data peminjaman aktif tidak ditemukan.'], 400);

        $jumlah = $request->jumlah ?? $peminjaman->jumlah;

        $peminjaman->update([
            'jumlah_kembali' => $jumlah,
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()
        ]);

        Book::where('nomor_buku', $request->nomor_buku)->increment('jumlah', $jumlah);

        return response()->json(['message' => 'Buku berhasil dikembalikan!']);
    }
}