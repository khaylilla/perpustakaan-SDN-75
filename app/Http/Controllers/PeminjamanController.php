<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    // index
public function index(Request $request)
{
    $query = Peminjaman::query();

    if($request->filter_type && $request->filter_date){
        if($request->filter_type == 'hari'){
            $query->whereDate('tanggal_pinjam', $request->filter_date);
        } elseif($request->filter_type == 'bulan'){
            $month = date('m', strtotime($request->filter_date));
            $year = date('Y', strtotime($request->filter_date));
            $query->whereMonth('tanggal_pinjam', $month)
                  ->whereYear('tanggal_pinjam', $year);
        } elseif($request->filter_type == 'tahun'){
            $year = date('Y', strtotime($request->filter_date));
            $query->whereYear('tanggal_pinjam', $year);
        }
    }

    $peminjaman = $query->get();
    return view('admin.riwayat.peminjaman.peminjaman', compact('peminjaman'));
}

// download PDF
public function downloadPDF(Request $request)
{
    $query = Peminjaman::query();

    if($request->filter_type && $request->filter_date){
        if($request->filter_type == 'hari'){
            $query->whereDate('tanggal_pinjam', $request->filter_date);
        } elseif($request->filter_type == 'bulan'){
            $month = date('m', strtotime($request->filter_date));
            $year = date('Y', strtotime($request->filter_date));
            $query->whereMonth('tanggal_pinjam', $month)
                  ->whereYear('tanggal_pinjam', $year);
        } elseif($request->filter_type == 'tahun'){
            $year = date('Y', strtotime($request->filter_date));
            $query->whereYear('tanggal_pinjam', $year);
        }
    }

    $peminjaman = $query->get();

    $pdf = \PDF::loadView('admin.riwayat.peminjaman.pdf', compact('peminjaman'));
    return $pdf->download('peminjaman.pdf');
}
}
