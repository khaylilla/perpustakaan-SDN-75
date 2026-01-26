<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::query();

        if ($request->keyword) {
            $query->where('judul', 'like', '%' . $request->keyword . '%')
                  ->orWhere('isi', 'like', '%' . $request->keyword . '%');
        }

        $artikels = $query->latest()->get();

        return view('admin.dataartikel', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:Informasi/Pengumuman,Berita,Artikel',
            'judul' => 'required|max:255',
            'subjudul' => 'nullable|max:255',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('artikels', 'public');
        }

        Artikel::create([
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'isi' => $request->isi,
            'foto' => $fotoPath,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('auth.artikel_detail', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:Informasi/Pengumuman,Berita,Artikel',
            'judul' => 'required|max:255',
            'subjudul' => 'nullable|max:255',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
        ]);

        $artikel = Artikel::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($artikel->foto && Storage::disk('public')->exists($artikel->foto)) {
                Storage::disk('public')->delete($artikel->foto);
            }
            $fotoPath = $request->file('foto')->store('artikels', 'public');
            $artikel->foto = $fotoPath;
        }

        $artikel->update([
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'isi' => $request->isi,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        // Hapus foto jika ada
        if ($artikel->foto && Storage::disk('public')->exists($artikel->foto)) {
            Storage::disk('public')->delete($artikel->foto);
        }

        $artikel->delete();

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil dihapus!');
    }
}

