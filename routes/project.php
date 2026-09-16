<?php

use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Tampilan Form Buat Project
    Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');

    // Simpan Project Baru
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});