<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        // Mengambil ulasan terbaru
        $reviews = Review::orderBy('created_at', 'desc')->get();
        
        // Pastikan nama view ini sesuai dengan lokasi file blade kamu
        // Jika file kamu ada di resources/views/auth/review.blade.php, gunakan 'auth.review'
        return view('auth.review', compact('reviews'));
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'name'    => 'required|string|max:100',
            'role'    => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        // Simpan ke database
        Review::create([
            'name'    => $request->name,
            'role'    => $request->role,
            'message' => $request->message,
        ]);

        // Redirect balik dengan pesan sukses untuk memicu SweetAlert
        return redirect()->back()->with('success', 'Ulasan kamu berhasil dikirim! ✨');
    }
}