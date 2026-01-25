<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\DNS1D;
use PDF;

class BookController extends Controller
{
    public function index(Request $request)
{
    $query = Book::query();

    if ($request->keyword) {
        $query->where('judul', 'like', '%'.$request->keyword.'%')
              ->orWhere('penulis', 'like', '%'.$request->keyword.'%')
              ->orWhere('kategori', 'like', '%'.$request->keyword.'%');
    }

    if ($request->filter_kategori) {
        $query->where('kategori', $request->filter_kategori);
    }

    if ($request->filter_tahun) {
        $query->where('tahun_terbit', $request->filter_tahun);
    }

    $books = $query->orderBy('kategori')
               ->orderBy('tahun_terbit')
               ->get()
               ->collect();

    $allKategori = Book::select('kategori')->distinct()->pluck('kategori');
    $allTahun = Book::select('tahun_terbit')->distinct()->pluck('tahun_terbit');

    return view('admin.datakoleksi', compact('books', 'allKategori', 'allTahun'));
}

    public function store(Request $request)
    {
        $request->validate([
            'cover.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'ebook_url' => 'nullable|url',
            'ebook_file' => 'nullable|mimes:pdf|max:10240',
            'barcode' => 'required|string|max:100|unique:books,barcode',
            'nomor_buku' => 'nullable|string|max:50',
            'rak' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:100',
            'jumlah' => 'nullable|integer|min:0',
        ]);

        // Generate nomor_buku dari barcode jika belum ada
        $nomor_buku = $request->nomor_buku;
        if (!$nomor_buku) {
            $year = now()->year;
            $nomor_buku = 'BK-' . $year . '-' . $request->barcode;
        }

        // Handle e-book (prioritas: file upload > URL)
        $ebook = null;
        if ($request->hasFile('ebook_file')) {
            $ebook = $request->file('ebook_file')->store('ebooks', 'public');
        } elseif ($request->ebook_url) {
            $ebook = $request->ebook_url;
        }

        $coverPaths = [];
        if ($request->hasFile('cover')) {
            foreach ($request->file('cover') as $file) {
                $coverPaths[] = $file->store('covers', 'public');
            }
        }

        Book::create([
            'cover' => json_encode($coverPaths),
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'ebook' => $ebook,
            'barcode' => $request->barcode,
            'nomor_buku' => $nomor_buku,
            'rak' => $request->rak,
            'status' => $request->status,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('admin.datakoleksi')->with('success', 'Data koleksi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'cover.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'ebook_url' => 'nullable|url',
            'ebook_file' => 'nullable|mimes:pdf|max:10240',
            'barcode' => 'required|string|max:100|unique:books,barcode,' . $id,
            'nomor_buku' => 'nullable|string|max:50',
            'rak' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:100',
            'jumlah' => 'nullable|integer|min:0',
        ]);

        // Generate nomor_buku dari barcode jika belum ada atau jika barcode berubah
        $nomor_buku = $request->nomor_buku;
        if (!$nomor_buku || $book->barcode !== $request->barcode) {
            $year = now()->year;
            $nomor_buku = 'BK-' . $year . '-' . $request->barcode;
        }

        // Handle e-book update
        $ebook = $book->ebook;
        if ($request->hasFile('ebook_file')) {
            // Hapus file lama jika ada dan bukan URL
            if ($book->ebook && strpos($book->ebook, 'http') !== 0) {
                if (Storage::disk('public')->exists($book->ebook)) {
                    Storage::disk('public')->delete($book->ebook);
                }
            }
            $ebook = $request->file('ebook_file')->store('ebooks', 'public');
        } elseif ($request->ebook_url) {
            // Hapus file lama jika ada dan user ganti dengan URL
            if ($book->ebook && strpos($book->ebook, 'http') !== 0) {
                if (Storage::disk('public')->exists($book->ebook)) {
                    Storage::disk('public')->delete($book->ebook);
                }
            }
            $ebook = $request->ebook_url;
        }

        if ($request->hasFile('cover')) {
            $oldCovers = json_decode($book->cover, true);
            if ($oldCovers) {
                foreach ($oldCovers as $oldCover) {
                    if (Storage::disk('public')->exists($oldCover)) {
                        Storage::disk('public')->delete($oldCover);
                    }
                }
            }
            $newCovers = [];
            foreach ($request->file('cover') as $file) {
                $newCovers[] = $file->store('covers', 'public');
            }
            $book->cover = json_encode($newCovers);
        }

        $book->fill($request->except(['cover', 'nomor_buku', 'barcode', 'ebook_url', 'ebook_file']));
        $book->nomor_buku = $nomor_buku;
        $book->barcode = $request->barcode;
        $book->ebook = $ebook;
        $book->save();

        return redirect()->route('admin.datakoleksi')->with('success', 'Data koleksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $covers = json_decode($book->cover, true);
        if ($covers) {
            foreach ($covers as $cover) {
                if (Storage::disk('public')->exists($cover)) {
                    Storage::disk('public')->delete($cover);
                }
            }
        }

        $book->delete();

        return redirect()->route('admin.datakoleksi')->with('success', 'Data koleksi berhasil dihapus!');
    }

    public function exportPDF(Request $request)
    {
        $query = Book::query();

        if($request->keyword){
            $query->where('judul','like','%'.$request->keyword.'%')
                  ->orWhere('penulis','like','%'.$request->keyword.'%')
                  ->orWhere('kategori','like','%'.$request->keyword.'%');
        }

        if ($request->filter_kategori) $query->where('kategori', $request->filter_kategori);
        if ($request->filter_tahun) $query->where('tahun_terbit', $request->filter_tahun);

        $books = $query->orderBy('kategori')->orderBy('tahun_terbit')->get();

        // Generate barcode on-the-fly di blade PDF
        $pdf = PDF::loadView('admin.koleksi_pdf', compact('books'));
        return $pdf->stream('Data_Koleksi.pdf');
    }

    public function showCard($id)
    {
        $book = Book::findOrFail($id);

        // Generate barcode on-the-fly
        $barcode = DNS1D::getBarcodeSVG($book->id, 'C128', 2, 50);

        return view('admin.card', compact('book', 'barcode'));
    }

    public function showBook($id)
    {
        $book = Book::findOrFail($id);
        return view('auth.show', compact('book'));
    }

}
