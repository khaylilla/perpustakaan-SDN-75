<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Guru;
use App\Models\Umum;

class ProfileController extends Controller
{
    // Helper method untuk mendapatkan user berdasarkan login type
    private function getUserByLoginType()
    {
        $loginAs = session('login_as');
        $user = Auth::user();

        if ($loginAs === 'siswa') {
            return User::find($user->id);
        } elseif ($loginAs === 'guru') {
            return Guru::find($user->id);
        } elseif ($loginAs === 'umum') {
            return Umum::find($user->id);
        }

        return $user;
    }

    public function show()
    {
        $user = $this->getUserByLoginType();
        $loginAs = session('login_as');
        
        return view('auth.profile', compact('user', 'loginAs'));
    }

    public function update(Request $request)
{
    $loginAs = session('login_as');
    $user = $this->getUserByLoginType();

    // 1. Validasi
    $request->validate([
        'nama' => 'required|string|max:255',
        'foto' => 'nullable|image|max:2048', // Maksimal 2MB
    ]);

    // Validasi dinamis berdasarkan login type
    if ($loginAs === 'siswa') {
        $request->validate([
            'nisn' => 'required|string|max:20',
            'asal_sekolah' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:50',
        ]);
    } else {
        $request->validate([
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string|max:255',
            'nohp' => 'nullable|string|max:20',
            'tgl_lahir' => 'nullable|date',
        ]);
        if ($loginAs === 'guru') {
            $request->validate([
                'nip' => 'required|string|max:20',
            ]);
        }
    }

    // 2. Isi data manual agar lebih aman
    $user->nama = $request->nama;

    if ($loginAs === 'siswa') {
        // SISWA: hanya ada nama, nisn, asal_sekolah, kelas, foto
        $user->nisn = $request->nisn;
        $user->asal_sekolah = $request->asal_sekolah;
        $user->kelas = $request->kelas;
    } else {
        // GURU & UMUM: ada email, alamat, nohp, tgl_lahir
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        $user->nohp = $request->nohp;
        $user->tgl_lahir = $request->tgl_lahir;
        
        if ($loginAs === 'guru') {
            // GURU tambahan: nip
            $user->nip = $request->nip;
        }
    }

    // 3. Handle foto upload (Lakukan SEBELUM save utama)
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($user->foto && Storage::disk('public')->exists('foto/' . $user->foto)) {
            Storage::disk('public')->delete('foto/' . $user->foto);
        }

        // Simpan foto baru dengan nama unik
        $file = $request->file('foto');
        $namaFoto = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('foto', $namaFoto, 'public');

        // Masukkan nama file ke objek user
        $user->foto = $namaFoto;
    }

    // 4. Simpan semua perubahan sekaligus
    $user->save();

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
}

    // ✅ Tambahan: Ubah Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui!');
    }
}
