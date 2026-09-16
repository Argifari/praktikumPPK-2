<?php

namespace App\Http\Controllers\Task; // Sesuai aturan isolasi folder Programmer 3

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\Task\StoreTaskRequest;   // Import Request Validasi
use App\Http\Requests\Task\UpdateTaskRequest;  // Import Request Validasi

class TaskController extends Controller
{
    // Menampilkan tasks & kalkulasi progres % (SRS-FR-07)
    public function index($projectId, Request $request)
    {
        $project = Project::findOrFail($projectId);

        // TODO NFR Otorisasi: $this->authorize('view', $project);

        $query = $project->tasks();

        // Fitur Filter & Sorting (Parameter Query Builder aman dari SQL Injection)
        if ($request->has('sort_by')) {
            $sortBy = $request->get('sort_by'); 
            if ($sortBy === 'due_date') {
                $query->orderBy('due_date', 'asc');
            } elseif ($sortBy === 'priority') {
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
            }
        }

        $tasks = $query->get();

        // Optimasi: Kalkulasi rasio progres langsung dari Collection di memori
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        
        // Menghitung rumus rasional SRS-FR-07
        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'progress_percentage' => $progressPercentage,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'data' => $tasks
        ], 200);
    }

    // POST /api/projects/{id}/tasks
    public function store(StoreTaskRequest $request, $projectId)
    {
        // NFR: Request otomatis tervalidasi oleh StoreTaskRequest

        $task = Task::create([
            'project_id' => $projectId,
            ...$request->validated(), // Mengambil data bersih yang sudah lolos validasi
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    // PUT /api/tasks/{id}
    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        // TODO NFR Otorisasi: $this->authorize('update', $task);

        // NFR: Request otomatis tervalidasi oleh UpdateTaskRequest
        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ], 200);
    }

    // PATCH /api/tasks/{id}/status (Update cepat status)
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // TODO NFR Otorisasi: $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => $task
        ], 200);
    }

    // DELETE /api/tasks/{id}
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        // TODO NFR Otorisasi: $this->authorize('delete', $task);
        
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ], 200);
    }
}