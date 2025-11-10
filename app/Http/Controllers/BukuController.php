<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // 🔍 Filter pencarian
        if ($request->filled('search')) {
            $search = $request->get('search');
            $searchType = $request->get('searchType', 'all');

            $query->where(function ($q) use ($search, $searchType) {
                if ($searchType === 'all') {
                    $q->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('penulis', 'LIKE', "%{$search}%")
                      ->orWhere('penerbit', 'LIKE', "%{$search}%")
                      ->orWhere('kategori', 'LIKE', "%{$search}%")
                      ->orWhere('tahun_terbit', 'LIKE', "%{$search}%"); // ✅ diperbaiki
                } else {
                    $q->where($searchType, 'LIKE', "%{$search}%");
                }
            });
        }

        // 📅 Urutkan
        if ($request->filled('sort')) {
            switch ($request->get('sort')) {
                case 'terbaru':
                    $query->orderBy('tahun_terbit', 'desc'); // ✅ diperbaiki
                    break;
                case 'terlama':
                    $query->orderBy('tahun_terbit', 'asc'); // ✅ diperbaiki
                    break;
                case 'judul_az':
                    $query->orderBy('judul', 'asc');
                    break;
                case 'judul_za':
                    $query->orderBy('judul', 'desc');
                    break;
            }
        }

        // 📚 Ambil data buku dengan pagination
        $books = $query->paginate(12);

        // 🏷️ Ambil semua kategori unik untuk dropdown
        $kategoriList = Book::select('kategori')->distinct()->pluck('kategori');

        return view('auth.buku', [
            'books' => $books,
            'kategoriList' => $kategoriList,
            'selectedKategori' => $request->kategori ?? '',
            'selectedTahun' => $request->tahun_terbit ?? '',
            'search' => $request->search ?? '',
        ]);
    }
}
