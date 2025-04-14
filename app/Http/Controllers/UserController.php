<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Fungsi untuk ke halaman utama
     */
    public function index()
    {
        // ambil semua data user
        $users = User::all();

        // arahkan pengguna dan kirim datanya ke halaman users
        return view('users.index', compact('users'));
    }

    /**
     * Fungsi untuk ke halaman form dan menambah data
     */

    public function create(){
        // arahkan pengguna ke form
        return view('users.form');
    }

    /**
     * Fungsi untuk ke menyimpan data ke database
     */
    public function store(Request $request)
    {
        // validasi hasil input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4',
            'role' => 'required'
        ]);

        // simpan data ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role
        ]);

        // arahkan pengguna ke halaman users
        return redirect()->route('users.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Fungsi untuk ke halaman form dan mengedit data
     */
    public function edit(string $id)
    {
        // ambil data user berdasarkan id
        $user = User::findOrFail($id);

        // arahkan pengguna ke form dan kirim data user
        return view('users.form', compact('user'));
    }

    /**
     * Fungsi untuk ke menyimpan data yang diperbarui ke database
     */
    public function update(Request $request, string $id)
    {
        // ambil data user berdasarkan id
        $user = User::findOrFail($id);

        // validasi hasil input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $id,
            'role' => 'required',
            'password' => 'required|min:4',
        ]);

        // perbarui data yang ada di database
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // arahkan pengguna ke halaman users
        return redirect()->route('users.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Fungsi untuk ke menghapus data
     */
    public function destroy(string $id)
    {
        // ambil data user berdasarkan id lalu hapus
        $user = User::findOrFail($id)->delete();

        // arahkan pengguna ke halaman users
        return redirect()->route('users.index')->with('success', 'Data berhasil dihapus');
    }
}
