<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProjectMemberController extends Controller
{
    public function store(InviteMemberRequest $request, Project $project): JsonResponse
    {
        $this->authorize('manageMembers', $project);

        $userToInvite = User::where('email', $request->email)->firstOrFail();
        $project->members()->attach($userToInvite->id);

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan ke project.',
            'data' => $userToInvite->only(['id', 'name', 'email'])
        ], 201);
    }

    public function destroy(Project $project, User $user): JsonResponse
    {
        $this->authorize('manageMembers', $project);

        $project->members()->detach($user->id);

        return response()->json([
            'message' => 'Anggota berhasil dihapus dari project.'
        ], 200);
    }
}