<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;

class DendaController extends Controller
{
    // Halaman denda dengan search & filter tanggal
    public function index(Request $request)
    {
        $query = Denda::query();

        // Search berdasarkan keyword (nama, npm, judul_buku)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%");
            });
        }

        // Filter tanggal pinjam
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        $denda = $query->latest()->get();

        // Ambil peminjaman yang masih "dipinjam" tetapi sudah lewat tanggal kembali
        $hariIni = Carbon::now()->toDateString();
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', $hariIni)
            ->get();

        $nominalPerHari = 1000;

        $peminjamanAsDenda = $peminjamanTerlambat->map(function($p) use ($nominalPerHari) {
            $hariTerlambat = Carbon::parse($p->tanggal_kembali)->diffInDays(Carbon::now());
            $totalDenda = $hariTerlambat * $nominalPerHari;

            return (object) [
                'id' => 'p-' . $p->id,
                'nama' => $p->nama,
                'npm' => $p->npm,
                'judul_buku' => $p->judul_buku,
                'nomor_buku' => $p->nomor_buku,
                'tanggal_pinjam' => $p->tanggal_pinjam,
                'tanggal_kembali' => $p->tanggal_kembali,
                'hari_terlambat' => $hariTerlambat,
                'total_denda' => $totalDenda,
            ];
        });

        // Gabungkan data denda manual dengan peminjaman terlambat
        $merged = collect();
        // Pastikan semua item berupa objek dengan properti yang sama seperti view harapkan
        foreach ($denda as $d) {
            $merged->push((object) $d->toArray());
        }
        foreach ($peminjamanAsDenda as $pd) {
            $merged->push($pd);
        }

        // Urutkan berdasarkan tanggal_pinjam desc
        $merged = $merged->sortByDesc(function($item) {
            return isset($item->tanggal_pinjam) ? strtotime($item->tanggal_pinjam) : 0;
        })->values();

        return view('admin.riwayat.denda', ['denda' => $merged]);
    }

    // Simpan denda baru (manual)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'nomor_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'hari_terlambat' => 'required|numeric',
            'total_denda' => 'required|numeric',
        ]);

        Denda::create($request->all());

        return redirect()->back()->with('success', 'Denda berhasil ditambahkan!');
    }

    // Update denda
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'nomor_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'hari_terlambat' => 'required|numeric',
            'total_denda' => 'required|numeric',
        ]);

        $denda = Denda::findOrFail($id);
        $denda->update($request->all());

        return redirect()->back()->with('success', 'Denda berhasil diperbarui!');
    }

    // Hapus denda
    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();

        return response()->json(['message' => 'Denda berhasil dihapus']);
    }

    // Export PDF
    public function exportPdf()
    {
        $denda = Denda::latest()->get();
        $pdf = \PDF::loadView('admin.riwayat.denda_pdf', compact('denda'))->setPaper('a4', 'landscape');
        return $pdf->download('data_denda.pdf');
    }
}
