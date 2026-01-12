<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsenController extends Controller
{
    // ================================
    // TAMPILAN DATA ABSEN
    // ================================
    public function index(Request $request)
    {
        $keyword    = $request->get('keyword');
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

        // Query dasar
        $query = Absen::query();

        // Pencarian
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('npm', 'like', "%$keyword%")
                  ->orWhere('tanggal', 'like', "%$keyword%");
            });
        }

        // Filter tanggal
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        // Ambil final data
        $absens = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.dataabsen', compact('absens'));
    }

    // ================================
    // TAMBAH DATA ABSEN
    // ================================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50',
            'tanggal' => 'required|date',
        ]);

        Absen::create($request->only('nama', 'npm', 'tanggal'));

        return redirect()->route('admin.dataabsen')
                         ->with('success', 'Data absen berhasil ditambahkan.');
    }

    // ================================
    // UPDATE DATA ABSEN
    // ================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50',
            'tanggal' => 'required|date',
        ]);

        $absen = Absen::findOrFail($id);
        $absen->update($request->only('nama', 'npm', 'tanggal'));

        return redirect()->route('admin.dataabsen')
                         ->with('success', 'Data absen berhasil diperbarui.');
    }

    // ================================
    // HAPUS DATA ABSEN
    // ================================
    public function destroy($id)
    {
        $absen = Absen::findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Data absen berhasil dihapus.');
    }


    // ================================
    // CETAK PDF
    // ================================
    public function printPdf(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

        // Query dasar
        $query = Absen::orderBy('tanggal', 'desc');

        // Filter jika ada range tanggal
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        $absens = $query->get();

        $pdf = Pdf::loadView('admin.absen_pdf', compact('absens'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("data_absen.pdf");
    }

    // ================================
    // EXPORT ABSEN GROUPED (DAY / MONTH / YEAR)
    // ================================
   public function exportAbsens(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

        $query = Absen::orderBy('tanggal', 'asc');

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        $absens = $query->get();

        $pdf = Pdf::loadView('admin.absen_pdf', compact('absens'))
                ->setPaper('a4', 'portrait'); // <-- ini A4 portrait

        return $pdf->download('data_absen.pdf');
    }

    public function printFilteredPdf(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

        // Query dasar
        $query = Absen::orderBy('tanggal', 'asc');

        // Filter jika ada range tanggal
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        $absens = $query->get();

        // Load Blade PDF khusus full tabel
        $pdf = Pdf::loadView('admin.absen_pdf_full', compact('absens'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("data_absen.pdf");
    }

    public function scanPage()
    {
        return view('admin.scan_absen');
    }

    public function getUser($npm)
    {
        $user = \App\Models\User::where('npm', $npm)->first();

        if (!$user) {
            return response()->json(['nama' => null]);
        }

        return response()->json([
            'nama' => $user->nama,
        ]);
    }

    public function storeScan(Request $request)
    {
        $request->validate([
            'npm' => 'required|string',
            'nama' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        Absen::create([
            'npm' => $request->npm,
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'user_id' => null,
        ]);

        return response()->json(['message' => 'Absen berhasil dicatat!']);
    }
}
