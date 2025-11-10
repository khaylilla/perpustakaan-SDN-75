<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dataUser(Request $request)
    {
        $query = User::query();

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where('nama', 'like', "%$keyword%")
                  ->orWhere('npm', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
        }

        $users = $query->get();

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
}

