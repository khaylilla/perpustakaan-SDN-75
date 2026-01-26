<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\User;
use App\Models\Guru;
use App\Models\Umum;
use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->mode ?? 'bulan';
        $start = $request->start;
        $end   = $request->end;
        $kelasFilter = $request->kelas;

        // ======== GET SEMUA KELAS ========
        $semuaKelas = User::select('kelas')
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // ======== FILTER RANGE ========
        $queryRange = function($query) use ($mode, $start, $end, $kelasFilter) {
            if ($kelasFilter) {
                $query->whereHas('user', function($q) use ($kelasFilter) {
                    $q->where('kelas', $kelasFilter);
                });
            }
            if ($mode == 'hari') {
                $date = $start ?? now()->toDateString();
                $query->whereDate('created_at', $date);
            } elseif ($mode == 'bulan') {
                $carbon = $start ? Carbon::parse($start) : now();
                $query->whereYear('created_at', $carbon->year)
                      ->whereMonth('created_at', $carbon->month);
            } elseif ($mode == 'tahun') {
                $tahun = $start ? Carbon::parse($start)->year : now()->year;
                $query->whereYear('created_at', $tahun);
            } elseif ($mode == 'range_tahun') {
                $tahunAwal = $start ? Carbon::parse($start)->year : 2025;
                $tahunAkhir = $end ? Carbon::parse($end)->year : now()->year;
                $query->whereYear('created_at', '>=', $tahunAwal)
                      ->whereYear('created_at', '<=', $tahunAkhir);
            }
        };

        // ======== STATISTIK ========
        $totalPengunjung = Absen::when(true, $queryRange)->count();
        $pengunjungHarian = Absen::whereDate('created_at', now()->toDateString())->count();
        
        if ($kelasFilter) {
            $totalUser = User::where('kelas', $kelasFilter)->count();
        } else {
            $totalUser = User::count() + Guru::count() + Umum::count();
        }
        
        $totalBuku = Book::count();
        
        // Filter peminjaman berdasarkan kelas jika ada
        $peminjamanQuery = Peminjaman::where('status', 'dipinjam');
        $peminjamanKembaliQuery = Peminjaman::where('status', 'dikembalikan');
        
        if ($kelasFilter) {
            $peminjamanQuery = $peminjamanQuery->whereIn('nama', User::where('kelas', $kelasFilter)->pluck('nama'));
            $peminjamanKembaliQuery = $peminjamanKembaliQuery->whereIn('nama', User::where('kelas', $kelasFilter)->pluck('nama'));
        }
        
        $totalPeminjaman = $peminjamanQuery->when(true, $queryRange)->count();
        $peminjamanBulanan = $peminjamanQuery->whereMonth('created_at', now()->month)->count();
        $totalPengembalian = $peminjamanKembaliQuery->when(true, $queryRange)->count();

        // ======== TOP DATA ========
        $bukuFavoritQuery = Peminjaman::select('judul_buku', DB::raw('COUNT(*) as total'));
        if ($kelasFilter) {
            $bukuFavoritQuery = $bukuFavoritQuery->whereIn('nama', User::where('kelas', $kelasFilter)->pluck('nama'));
        }
        
        $bukuFavorit = $bukuFavoritQuery->when(true, $queryRange)
            ->groupBy('judul_buku')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $userAktifQuery = Peminjaman::select('nama', DB::raw('COUNT(*) as total'));
        if ($kelasFilter) {
            $userAktifQuery = $userAktifQuery->whereIn('nama', User::where('kelas', $kelasFilter)->pluck('nama'));
        }
        
        $userAktif = $userAktifQuery->when(true, $queryRange)
            ->groupBy('nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Tambahkan user dari tabel guru dan umum hanya jika tidak ada filter kelas
        if (!$kelasFilter) {
            $guruAktif = Guru::select('nama', DB::raw('1 as total'))
                ->limit(5)
                ->get();

            $umumumAktif = Umum::select('nama', DB::raw('1 as total'))
                ->limit(5)
                ->get();

            // Gabungkan ketiga sumber dan sort berdasarkan total
            $userAktif = $userAktif->concat($guruAktif)->concat($umumumAktif)
                ->groupBy('nama')
                ->map(function($group) {
                    return (object)[
                        'nama' => $group[0]->nama,
                        'total' => $group->sum('total')
                    ];
                })
                ->sortByDesc('total')
                ->take(5)
                ->values();
        }

        // ======== GRAFIK ========
        $labels = [];
        $grafikPeminjaman = [];
        $grafikPengembalian = [];
        $grafikUserAktif = [];

        // Helper function untuk filter kelas di grafik
        $applyKelasFilterToQuery = function($query) use ($kelasFilter) {
            if ($kelasFilter) {
                return $query->whereIn('nama', User::where('kelas', $kelasFilter)->pluck('nama'));
            }
            return $query;
        };

        // Fungsi hitung user aktif per periode
        $getUserAktif = function($query) use ($applyKelasFilterToQuery) {
            return $applyKelasFilterToQuery($query)->where(function($q) {
                $q->where('status', 'dipinjam')
                  ->orWhere('status', 'dikembalikan');
            })->distinct('nama')->count('nama');
        };

        if($mode == 'hari') {
            $labels = range(0,23);
            $date = $start ? Carbon::parse($start)->toDateString() : now()->toDateString();
            foreach($labels as $i){
                $query = Peminjaman::whereDate('created_at', $date)
                    ->whereRaw('HOUR(created_at) = ?', [$i]);
                $grafikPeminjaman[$i] = $applyKelasFilterToQuery($query)->count();

                $queryReturn = Peminjaman::whereDate('created_at', $date)
                    ->whereRaw('HOUR(created_at) = ?', [$i])
                    ->where('status','dikembalikan');
                $grafikPengembalian[$i] = $applyKelasFilterToQuery($queryReturn)->count();

                $grafikUserAktif[$i] = $getUserAktif(Peminjaman::whereDate('created_at', $date)
                    ->whereRaw('HOUR(created_at) = ?', [$i]));
            }
        } elseif($mode == 'bulan') {
            $carbon = Carbon::parse($start ?? now());
            $days = $carbon->daysInMonth;
            $labels = range(1,$days);
            foreach($labels as $i){
                $query = Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i);
                $grafikPeminjaman[$i-1] = $applyKelasFilterToQuery($query)->count();
                
                $queryReturn = Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i)
                    ->where('status','dikembalikan');
                $grafikPengembalian[$i-1] = $applyKelasFilterToQuery($queryReturn)->count();
                
                $grafikUserAktif[$i-1] = $getUserAktif(Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i));
            }
        } elseif($mode == 'tahun') {
            $tahun = $start ? Carbon::parse($start)->year : now()->year;
            $labels = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            foreach(range(1,12) as $i){
                $query = Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i);
                $grafikPeminjaman[$i-1] = $applyKelasFilterToQuery($query)->count();
                
                $queryReturn = Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i)
                    ->where('status','dikembalikan');
                $grafikPengembalian[$i-1] = $applyKelasFilterToQuery($queryReturn)->count();
                
                $grafikUserAktif[$i-1] = $getUserAktif(Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i));
            }
        } elseif($mode == 'range_tahun') {
            $tahunAwal  = $start ? Carbon::parse($start)->year : 2025;
            $tahunAkhir = $end   ? Carbon::parse($end)->year : now()->year;
            $labels = range($tahunAwal,$tahunAkhir);
            foreach($labels as $i => $tahun){
                $query = Peminjaman::whereYear('created_at',$tahun);
                $grafikPeminjaman[$i] = $applyKelasFilterToQuery($query)->count();
                
                $queryReturn = Peminjaman::whereYear('created_at',$tahun)
                    ->where('status','dikembalikan');
                $grafikPengembalian[$i] = $applyKelasFilterToQuery($queryReturn)->count();
                
                $grafikUserAktif[$i] = $getUserAktif(Peminjaman::whereYear('created_at',$tahun));
            }
        }

        // ======== KATEGORI BUKU ========
        $kategoriBuku = Book::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total','kategori');

        // ======== STATISTIK BERDASARKAN KELAS ========
        $siswaPerKelas = User::select('kelas', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kelas')
            ->when($kelasFilter, function($query) use ($kelasFilter) {
                return $query->where('kelas', $kelasFilter);
            })
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas');

        return view('admin.dashboard', compact(
            'totalPengunjung','pengunjungHarian','totalUser','totalBuku',
            'totalPeminjaman','peminjamanBulanan','totalPengembalian',
            'bukuFavorit','userAktif','labels','grafikPeminjaman','grafikPengembalian','grafikUserAktif','kategoriBuku','siswaPerKelas','semuaKelas'
        ));
    }
}
