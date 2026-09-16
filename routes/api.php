<?php

use App\Http\Controllers\ProjectMemberController;

Route::middleware('auth:sanctum')->group(function () {
    // Invite Anggota
Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store']);
    // Remove Anggota
Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy']);
});
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
