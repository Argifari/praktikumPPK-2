<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Fitur 1 : Melihat daftar semua user
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Berhasil mengambil data user',
            'data' => $users
        ]);
    }

    // Fitur 2 : Menambahkan user baru
    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user'
        ]);

        // Create user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Akun pengguna berhasil dibuat',
            'data' => $user
        ], 201); // 201 = created
    }
}
