<?php

use App\Http\Controllers\ProjectMemberController;

Route::middleware('auth:sanctum')->group(function () {
    // Invite Anggota
Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store']);
    // Remove Anggota
Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy']);
});
