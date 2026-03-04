<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\User;
use App\Models\Umum;
use App\Models\Guru;
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
        $order      = $request->get('order', 'desc'); 
        $type       = $request->get('type');

        // Query dasar untuk absen
        $query = Absen::query();

        // Pencarian
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('npm', 'like', "%$keyword%")
                  ->orWhere('tanggal', 'like', "%$keyword%");
            });
        }

        // Filter tanggal: gunakan `created_at` (waktu rekam) agar konsisten dengan tampilan
        if ($start_date && $end_date) {
            $start = $start_date . ' 00:00:00';
            $end = $end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($start_date) {
            $query->whereDate('created_at', $start_date);
        } elseif ($end_date) {
            $query->whereDate('created_at', $end_date);
        }

        // Filter kategori berdasarkan keberadaan identifier di tabel masing-masing
        if ($type && in_array($type, ['siswa', 'guru', 'umum'])) {
            if ($type === 'siswa') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('users')
                      ->whereColumn('users.nisn', 'absens.npm');
                });
            } elseif ($type === 'guru') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('guru')
                      ->whereColumn('guru.nip', 'absens.npm');
                });
            } elseif ($type === 'umum') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('umum')
                      ->whereColumn('umum.email', 'absens.npm');
                });
            }
        }

        // Urutkan berdasarkan created_at agar konsisten dengan tampilan waktu
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';
        $absens = $query->orderBy('created_at', $order)->get();

        // Ambil data keseluruhan dari 3 tabel untuk referensi
        $allUsers = User::get()->map(function($item) {
            $item->type = 'users';
            return $item;
        });

        $allUmum = Umum::get()->map(function($item) {
            $item->type = 'umum';
            return $item;
        });

        $allGuru = Guru::get()->map(function($item) {
            $item->type = 'guru';
            return $item;
        });

        // Gabung ketiga koleksi
        $allPersons = $allUsers->concat($allUmum)->concat($allGuru);

        return view('admin.dataabsen', compact('absens', 'allPersons'));
    }

    // ================================
// TAMBAH DATA ABSEN (Perbaikan)
// ================================
public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'npm' => 'required|string|max:50',
        'tanggal' => 'required', // Hapus validasi |date agar format string manual aman
    ]);

    Absen::create([
        'nama'    => $request->nama,
        'npm'     => $request->npm,
        // Pastikan formatnya Y-m-d H:i:s agar jam masuk ke DB
        'tanggal' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d H:i:s'),
    ]);

    return redirect()->route('admin.dataabsen')
                     ->with('success', 'Data absen berhasil ditambahkan.');
}

// ================================
// UPDATE DATA ABSEN (Perbaikan)
// ================================
public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'npm' => 'required|string|max:50',
        'tanggal' => 'required',
    ]);

    $absen = Absen::findOrFail($id);
    $absen->update([
        'nama'    => $request->nama,
        'npm'     => $request->npm,
        'tanggal' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d H:i:s'),
    ]);

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
        $order      = $request->get('order', 'desc');
        $type       = $request->get('type');

        $query = Absen::query();

        // Filter tanggal menggunakan created_at
        if ($start_date && $end_date) {
            $start = $start_date . ' 00:00:00';
            $end = $end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($start_date) {
            $query->whereDate('created_at', $start_date);
        } elseif ($end_date) {
            $query->whereDate('created_at', $end_date);
        }

        // Filter kategori
        if ($type && in_array($type, ['siswa', 'guru', 'umum'])) {
            if ($type === 'siswa') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('users')
                      ->whereColumn('users.nisn', 'absens.npm');
                });
            } elseif ($type === 'guru') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('guru')
                      ->whereColumn('guru.nip', 'absens.npm');
                });
            } elseif ($type === 'umum') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('umum')
                      ->whereColumn('umum.email', 'absens.npm');
                });
            }
        }

        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';
        $absens = $query->orderBy('created_at', $order)->get();

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
        $order      = $request->get('order', 'desc');
        $type       = $request->get('type');

        $query = Absen::query();

        if ($start_date && $end_date) {
            $start = $start_date . ' 00:00:00';
            $end = $end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($start_date) {
            $query->whereDate('created_at', $start_date);
        } elseif ($end_date) {
            $query->whereDate('created_at', $end_date);
        }

        if ($type && in_array($type, ['siswa', 'guru', 'umum'])) {
            if ($type === 'siswa') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('users')->whereColumn('users.nisn', 'absens.npm');
                });
            } elseif ($type === 'guru') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('guru')->whereColumn('guru.nip', 'absens.npm');
                });
            } elseif ($type === 'umum') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('umum')->whereColumn('umum.email', 'absens.npm');
                });
            }
        }

        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';
        $absens = $query->orderBy('created_at', $order)->get();

        $pdf = Pdf::loadView('admin.absen_pdf', compact('absens'))
                ->setPaper('a4', 'portrait'); // <-- ini A4 portrait

        return $pdf->download('data_absen.pdf');
    }

    public function printFilteredPdf(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $order      = $request->get('order', 'desc');
        $type       = $request->get('type');

        $query = Absen::query();

        if ($start_date && $end_date) {
            $start = $start_date . ' 00:00:00';
            $end = $end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($start_date) {
            $query->whereDate('created_at', $start_date);
        } elseif ($end_date) {
            $query->whereDate('created_at', $end_date);
        }

        if ($type && in_array($type, ['siswa', 'guru', 'umum'])) {
            if ($type === 'siswa') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('users')->whereColumn('users.nisn', 'absens.npm');
                });
            } elseif ($type === 'guru') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('guru')->whereColumn('guru.nip', 'absens.npm');
                });
            } elseif ($type === 'umum') {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('umum')->whereColumn('umum.email', 'absens.npm');
                });
            }
        }

        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';
        $absens = $query->orderBy('created_at', $order)->get();

        // Load Blade PDF khusus full tabel
        $pdf = Pdf::loadView('admin.absen_pdf_full', compact('absens'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("data_absen.pdf");
    }

    public function scanPage()
    {
        return view('admin.scan_absen');
    }

    public function getUser($identifier)
    {
        // Cari di tabel users berdasarkan NISN
        $user = \App\Models\User::where('nisn', $identifier)->first();
        if ($user) {
            return response()->json([
                'nama' => $user->nama,
                'type' => 'users'
            ]);
        }

        // Cari di tabel guru berdasarkan NIP
        $guru = \App\Models\Guru::where('nip', $identifier)->first();
        if ($guru) {
            return response()->json([
                'nama' => $guru->nama,
                'type' => 'guru'
            ]);
        }

        // Cari di tabel umum berdasarkan Email
        $umum = \App\Models\Umum::where('email', $identifier)->first();
        if ($umum) {
            return response()->json([
                'nama' => $umum->nama,
                'type' => 'umum'
            ]);
        }

        // Tidak ditemukan di ketiga tabel
        return response()->json(['nama' => null]);
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
