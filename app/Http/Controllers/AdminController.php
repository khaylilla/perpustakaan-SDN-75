<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Umum;
use App\Models\Guru;
use App\Models\Notifikasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan data gabungan dari tabel User, Umum, dan Guru
     */
    public function index(Request $request)
    {
        $keyword = $request->has('keyword') && $request->keyword != '' ? $request->keyword : null;
        $category = $request->get('category');

        // Ambil data dari ketiga tabel
        $usersQuery = User::query();
        $umumQuery = Umum::query();
        $guruQuery = Guru::query();

        // Filter keyword jika ada
        if ($keyword) {
            $usersQuery->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('nisn', 'like', "%$keyword%");
            });
            
            $umumQuery->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
            });
            
            $guruQuery->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('nip', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
            });
        }

        // Ambil data dengan tipe untuk membedakan dari tabel mana
        $usersData = $usersQuery->get()->map(function($item) {
            $item->type = 'users';
            $item->identifier = $item->nisn ?? '';
            return $item;
        });

        $umumData = $umumQuery->get()->map(function($item) {
            $item->type = 'umum';
            $item->identifier = $item->email ?? '';
            return $item;
        });

        $guruData = $guruQuery->get()->map(function($item) {
            $item->type = 'guru';
            $item->identifier = $item->nip ?? '';
            return $item;
        });

        // Gabung ketiga koleksi (Logika asli dipertahankan)
        $users = $usersData->concat($umumData)->concat($guruData);

        return view('admin.datauser', compact('users'));
    }

    public function createUser()
    {
        return view('admin.createuser');
    }

    /**
     * Menyimpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.datauser')->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edituser', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:users,npm,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string',
        ]);

        $user->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
        ]);

        return redirect()->route('admin.datauser')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.datauser')->with('success', 'User berhasil dihapus!');
    }

    // --- NOTIFIKASI (TIDAK ADA YANG DIHAPUS) ---

    public function notifikasi(Request $request)
    {
        $query = Notifikasi::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('pesan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $notifikasi = $query->latest()->get();
        $totalNotif = Notifikasi::count();
        $notifBaru = Notifikasi::whereDate('created_at', today())->count();

        return view('admin.notifikasi', compact('notifikasi', 'totalNotif', 'notifBaru'));
    }

    public function notifikasiStore(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'pesan' => 'required',
        ]);

        Notifikasi::create($request->all());
        return redirect()->back()->with('success', 'Notifikasi berhasil ditambahkan!');
    }

    public function notifikasiUpdate(Request $request, $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update($request->all());
        return redirect()->back()->with('success', 'Notifikasi berhasil diperbarui!');
    }

    public function notifikasiDelete($id)
    {
        Notifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus!');
    }
}