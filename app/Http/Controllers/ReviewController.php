<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request) // Tambahkan parameter $request di sini
    {
        $query = Review::query();

        // 1. Logika Filter Kategori
        // Jika ada parameter 'category' di URL (contoh: ?category=Layanan)
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // 2. Mengambil ulasan (dengan filter jika ada) dan diurutkan terbaru
        $reviews = $query->orderBy('created_at', 'desc')->get();

        return view('auth.review', compact('reviews'));
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'name'     => 'required|string|max:100',
            'role'     => 'required|string|max:100',
            'category' => 'required|in:Fasilitas,Layanan,Buku', // Validasi pilihan kategori
            'rating'   => 'required|integer|min:1|max:5',
            'message'  => 'required|string',
        ]);

        Review::create($request->all());

        return redirect()->back()->with('success', 'Ulasan kamu berhasil dikirim! ✨');
    }
}