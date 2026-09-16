<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

    // Fitur 3 : Menghapus user + cleanup relasi
    public function destroy(User $user, Request $request)
    {
        // Proteksi hapus diri sendiri
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri'
            ], 403);
        }

        // Cleanup keanggotaan (guard: tabel pivot milik Programmer 2, belum ada migrasinya)
        if (Schema::hasTable('project_user')) {
            $user->belongsToMany(Project::class, 'project_user')->detach();
        }

        // Hapus token + user (project miliknya ikut terhapus via cascadeOnDelete)
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Akun pengguna berhasil dihapus'
        ]);
    }
}
