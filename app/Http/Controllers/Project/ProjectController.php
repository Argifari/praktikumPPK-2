<?php

use App\Services\ProjectService;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    // ... method store & create yang sudah kamu buat sebelumnya ...

    public function destroy(Project $project)
    {
        // Otorisasi Policy (Otomatis return 403 jika bukan owner)
        Gate::authorize('delete', $project);

        // Eksekusi hapus atomik
        $this->projectService->deleteProjectAtomically($project);

        return response()->json([
            'message' => 'Daftar tugas beserta seluruh task dan keanggotaan berhasil dihapus.'
        ], 200);
    }
}