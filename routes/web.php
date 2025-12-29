<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChipController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChipController::class, 'index']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/chips', [ChipController::class, 'store']);
    Route::get('/chips/{chip}/edit', [ChipController::class, 'edit']);
    Route::put('/chips/{chip}', [ChipController::class, 'update']);
    Route::delete('/chips/{chip}', [ChipController::class, 'destroy']);
    Route::post('/chips/{chip}/reply', [ChipController::class, 'reply']);

    Route::resource('tasks', TaskController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});

// Registration routes
Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', Register::class)
    ->middleware('guest');

// Login routes
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', Login::class)
    ->middleware('guest');

// Logout route
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');
