<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Menampilkan tasks & kalkulasi progres %
    public function index($projectId, Request $request)
    {
        $project = Project::findOrFail($projectId);

        $query = $project->tasks();

        // Fitur Filter & Sorting opsional berdasarkan query params
        if ($request->has('sort_by')) {
            $sortBy = $request->get('sort_by'); // 'due_date' atau 'priority'
            if ($sortBy === 'due_date') {
                $query->orderBy('due_date', 'asc');
            } elseif ($sortBy === 'priority') {
                // Urutan prioritas: high -> medium -> low
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
            }
        }

        $tasks = $query->get();

        // Query Agregasi SQL untuk menghitung persentase progres
        $totalTasks = $project->tasks()->count();
        $completedTasks = $project->tasks()->where('status', 'completed')->count();
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
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed',
            'created_by' => 'required|exists:users,id'
        ]);

        $task = Task::create([
            'project_id' => $projectId,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'created_by' => $request->created_by,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    // PUT
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ], 200);
    }

    // PATCH Update cepat status)
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $request->status]);

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
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ], 200);
    }
}