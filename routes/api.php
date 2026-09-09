<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;

// Route Public (Bisa diakses tanpa login)
Route::post('/login', [AuthController::class, 'login']);

// Route Protected (Hanya bisa diakses kalo udah login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route khusus admin (dilindungi middleware 'admin')
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('users', AdminUserController::class);
    });
});