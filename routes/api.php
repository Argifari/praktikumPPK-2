<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectMemberController;
use App\Http\Controllers\Task\TaskController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Project Members
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/projects/{project}/members',
        [ProjectMemberController::class, 'store']
    );

    Route::delete(
        '/projects/{project}/members/{user}',
        [ProjectMemberController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/projects/{project}/tasks',
        [TaskController::class, 'index']
    );

    Route::post(
        '/projects/{project}/tasks',
        [TaskController::class, 'store']
    );

    Route::put(
        '/tasks/{task}',
        [TaskController::class, 'update']
    );

    Route::patch(
        '/tasks/{task}/status',
        [TaskController::class, 'updateStatus']
    );

    Route::delete(
        '/tasks/{task}',
        [TaskController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/users', [
            AdminUserController::class,
            'index'
        ]);

        Route::post('/users', [
            AdminUserController::class,
            'store'
        ]);

        Route::delete('/users/{user}', [
            AdminUserController::class,
            'destroy'
        ]);

    });
});