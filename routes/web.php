<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

// Tampilan auth admin
Route::get('/login', fn () => view('auth.login'));
Route::get('/admin/users', fn () => view('admin.users'));
