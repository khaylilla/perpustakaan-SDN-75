<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Umum;
use App\Models\Guru;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan data gabungan dari tabel User, Umum, dan Guru
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        // Ambil data dari ketiga tabel dengan filter keyword
        $usersQuery = User::query();
        $umumQuery = Umum::query();
        $guruQuery = Guru::query();

        if ($keyword) {
            $usersQuery->where('nama', 'like', "%$keyword%")->orWhere('nisn', 'like', "%$keyword%");
            $umumQuery->where('nama', 'like', "%$keyword%")->orWhere('email', 'like', "%$keyword%");
            $guruQuery->where('nama', 'like', "%$keyword%")->orWhere('nip', 'like', "%$keyword%");
        }

        // Map data agar memiliki property 'type' untuk identifikasi tabel di View
        $usersData = $usersQuery->get()->map(function($item) {
            $item->type = 'users';
            $item->identifier = $item->nisn ?? '-';
            return $item;
        });

        $umumData = $umumQuery->get()->map(function($item) {
            $item->type = 'umum';
            $item->identifier = $item->email ?? '-';
            return $item;
        });

        $guruData = $guruQuery->get()->map(function($item) {
            $item->type = 'guru';
            $item->identifier = $item->nip ?? '-';
            return $item;
        });

        // Gabung semua data
        $users = $usersData->concat($umumData)->concat($guruData);

        return view('admin.datauser', compact('users'));
    }

    /**
     * Menyimpan user baru ke tabel yang sesuai
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|in:users,guru,umum',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        
        // Handle Password jika ada field password di form
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto', 'public');
        }

        // Simpan ke tabel yang benar berdasarkan 'type'
        if ($request->type === 'users') {
            User::create($data);
        } elseif ($request->type === 'guru') {
            Guru::create($data);
        } elseif ($request->type === 'umum') {
            Umum::create($data); // Pastikan Model Umum punya fillable 'email'
        }

        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Update data user secara dinamis sesuai tabel asalnya
     */
    public function update(Request $request, $id)
    {
        $type = $request->type; // Dikirim dari hidden input di modal edit
        $model = null;

        if ($type === 'users') { $model = User::findOrFail($id); }
        elseif ($type === 'guru') { $model = Guru::findOrFail($id); }
        elseif ($type === 'umum') { $model = Umum::findOrFail($id); }

        if ($model) {
            $data = $request->all();
            
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($model->foto) { Storage::disk('public')->delete($model->foto); }
                $data['foto'] = $request->file('foto')->store('foto', 'public');
            }

            $model->update($data);
            return redirect()->back()->with('success', 'Data berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }

    /**
     * Hapus user secara dinamis
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->type; // Diambil dari query string ?type=...
        $model = null;

        if ($type === 'users') { $model = User::find($id); }
        elseif ($type === 'guru') { $model = Guru::find($id); }
        elseif ($type === 'umum') { $model = Umum::find($id); }

        if ($model) {
            if ($model->foto) { Storage::disk('public')->delete($model->foto); }
            $model->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // --- BAGIAN NOTIFIKASI (TIDAK ADA YANG DIHAPUS) ---
    public function notifikasi(Request $request)
    {
        $query = Notifikasi::query();
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")->orWhere('pesan', 'like', "%{$keyword}%");
            });
        }
        $notifikasi = $query->latest()->get();
        $totalNotif = Notifikasi::count();
        $notifBaru = Notifikasi::whereDate('created_at', today())->count();
        return view('admin.notifikasi', compact('notifikasi', 'totalNotif', 'notifBaru'));
    }

    public function notifikasiStore(Request $request)
    {
        $request->validate(['judul' => 'required', 'pesan' => 'required']);
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