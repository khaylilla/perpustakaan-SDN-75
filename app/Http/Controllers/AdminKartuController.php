<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Umum;
use Carbon\Carbon;

class AdminKartuController extends Controller
{
    // Menampilkan daftar anggota
    public function index(Request $request)
    {
        // Collect dari 3 tabel dengan type dan foto
        $siswa = User::select('id', 'nama', 'nisn as identifier', \DB::raw("'siswa' as type"), 'foto', 'created_at')
            ->get();
        
        $guru = Guru::select('id', 'nama', 'nip as identifier', \DB::raw("'guru' as type"), 'foto', 'created_at')
            ->get();
        
        $umum = Umum::select('id', 'nama', 'email as identifier', \DB::raw("'umum' as type"), 'foto', 'created_at')
            ->get();
        
        // Merge semua data
        $anggota = collect([])
            ->merge($siswa)
            ->merge($guru)
            ->merge($umum)
            ->sortByDesc('created_at');

        // SEARCH
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $anggota = $anggota->filter(function($item) use ($keyword) {
                return stripos($item->nama, $keyword) !== false || 
                       stripos($item->identifier, $keyword) !== false;
            });
        }

        // FILTER BY TYPE
        if ($request->filled('type')) {
            $type = $request->type;
            $anggota = $anggota->filter(function($item) use ($type) {
                return $item->type === $type;
            });
        }

        return view('admin.kartu', ['anggota' => $anggota]);
    }

    // Generate ulang kartu
    public function regenerate(User $user)
    {
        $now = Carbon::now();

        // Untuk kebijakan baru: kartu berlaku selamanya.
        // Jika admin tetap menekan generate, kita perbolehkan dan hanya
        // mengembalikan informasi bahwa kartu "Berlaku Seumur Hidup".
        $user->touch(); // update timestamps jika masih ingin merekam aksi

        return response()->json([
            'success' => 'Kartu anggota berhasil digenerate ulang.',
            'masa_aktif' => 'Berlaku Seumur Hidup',
        ]);
    }
}
