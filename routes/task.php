<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;

/*
|--------------------------------------------------------------------------
| Rute Modul Task (Area Programmer 3)
|--------------------------------------------------------------------------
| Seluruh rute di bawah ini wajib dilindungi oleh middleware autentikasi
| untuk memenuhi standar NFR keamanan sistem.
|
*/

Route::middleware(['auth'])->group(function () {
    
    // Rute yang membutuhkan konteks Project ID
    Route::prefix('projects/{project}')->group(function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    });

    // Rute operasional langsung ke entitas Task
    Route::prefix('tasks')->group(function () {
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
});