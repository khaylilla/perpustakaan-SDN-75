<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::query();

        if ($request->keyword) {
            $query->where('judul', 'like', '%' . $request->keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->keyword . '%');
        }

        $artikels = $query->latest()->get();

        return view('admin.dataartikel', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'link' => 'required|url',
        ]);

        Artikel::create($request->all());

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'link' => 'required|url',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->update($request->only(['judul', 'deskripsi', 'link']));

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();

        return redirect()->route('admin.dataartikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
