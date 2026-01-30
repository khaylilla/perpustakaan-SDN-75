<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::query();

        // Pencarian berdasarkan judul atau isi
        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->keyword . '%')
                  ->orWhere('isi', 'like', '%' . $request->keyword . '%');
            });
        }

        $artikels = $query->latest()->get();

        return view('admin.dataartikel', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:Informasi/Pengumuman,Berita,Artikel',
            'judul'    => 'required|max:255',
            'subjudul' => 'nullable|max:255',
            'isi'      => 'required',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link'     => 'nullable|url',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('artikels', 'public');
        }

        Artikel::create($data);

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
            'judul'    => 'required|max:255',
            'subjudul' => 'nullable|max:255',
            'isi'      => 'required',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link'     => 'nullable|url',
        ]);

        $artikel = Artikel::findOrFail($id);
        
        // Ambil semua input kecuali foto
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($artikel->foto && Storage::disk('public')->exists($artikel->foto)) {
                Storage::disk('public')->delete($artikel->foto);
            }
            // Simpan foto baru dan masukkan ke array data
            $data['foto'] = $request->file('foto')->store('artikels', 'public');
        }

        // Update data (termasuk subjudul)
        $artikel->update($data);

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cari data berdasarkan ID
        $artikel = Artikel::findOrFail($id);

        // Hapus file foto dari storage fisik jika ada
        if ($artikel->foto && Storage::disk('public')->exists($artikel->foto)) {
            Storage::disk('public')->delete($artikel->foto);
        }

        // Hapus data dari database
        $artikel->delete();

        // Redirect kembali
        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil dihapus!');
    }
}