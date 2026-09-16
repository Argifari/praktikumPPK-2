<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller; // <-- Baris wajib ini
use App\Http\Requests\Project\InviteMemberRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProjectMemberController extends Controller
{
    public function store(InviteMemberRequest $request, Project $project): JsonResponse
    {
        // 1. Verifikasi otorisasi (Hanya owner yang boleh invite)
        Gate::authorize('manageMembers', $project);

        // 2. Cari pengguna berdasarkan email
        $userToInvite = User::where('email', $request->email)->firstOrFail();

        // 3. Cek apakah pengguna sudah terdaftar di project ini
        if ($project->members()->where('user_id', $userToInvite->id)->exists()) {
            return response()->json([
                'message' => 'Pengguna tersebut sudah menjadi anggota pada project ini.'
            ], 422);
        }

        // 4. Tambahkan pengguna ke tabel pivot project_user dengan role 'member'
        $project->members()->attach($userToInvite->id, ['role' => 'member']);

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan ke project.',
            'data' => $userToInvite->only(['id', 'name', 'email'])
        ], 201);
    }

    public function destroy(Project $project, User $user): JsonResponse
    {
        // 1. Verifikasi otorisasi (Hanya owner yang boleh menghapus anggota)
        Gate::authorize('manageMembers', $project);

        // 2. Cegah menghapus owner dari project-nya sendiri
        if ($user->id === $project->owner_id) {
            return response()->json([
                'message' => 'Pemilik project tidak dapat dihapus dari daftar anggota.'
            ], 422);
        }

        // 3. Hapus relasi dari tabel pivot project_user
        $project->members()->detach($user->id);

        return response()->json([
            'message' => 'Anggota berhasil dihapus dari project.'
        ], 200);
    }
}