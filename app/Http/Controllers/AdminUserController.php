<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminUserController extends Controller
{
    // List user
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Berhasil mengambil data user',
            'data' => $users
        ]);
    }

    // Tambah user
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user'
        ]);

        // Simpan user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Akun pengguna berhasil dibuat',
            'data' => $user
        ], 201); // 201 = created
    }

    // Hapus user
    public function destroy(User $user, Request $request)
    {
        // Cegah hapus diri
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri'
            ], 403);
        }

        // Lepas keanggotaan
        if (Schema::hasTable('project_user')) {
            $user->belongsToMany(Project::class, 'project_user')->detach();
        }

        // Hapus token user
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Akun pengguna berhasil dihapus'
        ]);
    }
}
