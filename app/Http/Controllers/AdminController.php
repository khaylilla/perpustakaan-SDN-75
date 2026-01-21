<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Umum;
use App\Models\Guru;
use App\Models\Notifikasi;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function dataUser(Request $request)
    {
        $keyword = $request->has('keyword') && $request->keyword != '' ? $request->keyword : null;
        $category = $request->get('category');

        // Ambil data dari ketiga tabel
        $users = User::query();
        $umum = Umum::query();
        $guru = Guru::query();

        // Filter keyword jika ada
        if ($keyword) {
            $users->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('nisn', 'like', "%$keyword%");
            });
            
            $umum->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
            });
            
            $guru->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('nip', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
            });
        }

        // Ambil data dengan tipe untuk membedakan dari tabel mana
        $usersData = $users->get()->map(function($item) {
            $item->type = 'users';
            $item->identifier = $item->nisn ?? '';
            return $item;
        });

        $umumData = $umum->get()->map(function($item) {
            $item->type = 'umum';
            $item->identifier = $item->email ?? '';
            return $item;
        });

        $guruData = $guru->get()->map(function($item) {
            $item->type = 'guru';
            $item->identifier = $item->nip ?? '';
            return $item;
        });

        // Gabung ketiga koleksi
        $users = $usersData->concat($umumData)->concat($guruData);

        return view('admin.datauser', compact('users'));
    }

    public function createUser()
    {
        return view('admin.createuser');
    }

    public function storeUser(Request $request)
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

    public function updateUser(Request $request, $id)
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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.datauser')->with('success', 'User berhasil dihapus!');
    }

public function notifikasi(Request $request)
{
    $query = Notifikasi::query();

    // ===== FILTER SEARCH =====
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('pesan', 'like', "%{$keyword}%");
        });
    }

    // ===== FILTER TANGGAL =====
    if ($request->filled('start_date')) {
        $start = $request->start_date;
        $query->whereDate('created_at', '>=', $start);
    }
    if ($request->filled('end_date')) {
        $end = $request->end_date;
        $query->whereDate('created_at', '<=', $end);
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

