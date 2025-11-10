<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'npm' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'nohp' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'npm' => $request->npm,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'tgl_lahir' => $request->tgl_lahir,
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            $fotoPath = $request->file('foto')->store('public/foto');
            $user->foto = basename($fotoPath);
            $user->save();
        }

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
