<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsenController extends Controller
{
    // 🔹 Tampilkan daftar absen dengan pengelompokan (hari, bulan, tahun)
    public function index(Request $request)
    {
        $groupBy = $request->get('group_by', 'day'); // default: per hari
        $keyword = $request->get('keyword');

        // Query dasar
        $query = Absen::query();

        // Jika ada pencarian
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('npm', 'like', "%$keyword%")
                  ->orWhere('tanggal', 'like', "%$keyword%");
            });
        }

        // Ambil data dan kelompokkan
        $absens = $query->orderBy('tanggal', 'desc')->get();

        $groupedAbsens = match ($groupBy) {
            'month' => $absens->groupBy(fn($item) => date('Y-m', strtotime($item->tanggal))),
            'year' => $absens->groupBy(fn($item) => date('Y', strtotime($item->tanggal))),
            default => $absens->groupBy(fn($item) => date('Y-m-d', strtotime($item->tanggal))),
        };

        return view('admin.dataabsen', compact('groupedAbsens', 'groupBy'));
    }

    // 🔹 Simpan absen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50',
            'prodi' => 'required|string|max:100',
            'tanggal' => 'required|date',
        ]);

        Absen::create($request->only('nama', 'npm', 'prodi', 'tanggal'));
        return redirect()->route('admin.dataabsen')->with('success', 'Data absen berhasil ditambahkan.');
    }

    // 🔹 Update absen
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50',
            'prodi' => 'required|string|max:100',
            'tanggal' => 'required|date',
        ]);

        $absen = Absen::findOrFail($id);
        $absen->update($request->only('nama', 'npm', 'prodi', 'tanggal'));

        return redirect()->route('admin.dataabsen')->with('success', 'Data absen berhasil diperbarui.');
    }

    // 🔹 Hapus absen
    public function delete($id)
    {
        $absen = Absen::findOrFail($id);
        $absen->delete();

        return redirect()->route('admin.dataabsen')->with('success', 'Data absen berhasil dihapus.');
    }

    // 🔹 Cetak PDF berdasarkan pengelompokan
    public function printPdf(Request $request)
    {
        $groupBy = $request->get('group_by', 'day');

        $absens = Absen::orderBy('tanggal', 'desc')->get();

        $groupedAbsens = match ($groupBy) {
            'month' => $absens->groupBy(fn($item) => date('Y-m', strtotime($item->tanggal))),
            'year' => $absens->groupBy(fn($item) => date('Y', strtotime($item->tanggal))),
            default => $absens->groupBy(fn($item) => date('Y-m-d', strtotime($item->tanggal))),
        };

        $pdf = Pdf::loadView('admin.absen_pdf', compact('groupedAbsens', 'groupBy'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("data_absen_{$groupBy}.pdf");
    }
}
