<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Lists CRUD
    Route::post('/lists', [\App\Http\Controllers\TaskListController::class, 'store'])->name('lists.store');
    Route::delete('/lists/{list}', [\App\Http\Controllers\TaskListController::class, 'destroy'])->name('lists.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('lists', \App\Http\Controllers\TodoListController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);
});

Route::middleware('auth')->group(function () {
    // Tasks CRUD
    Route::post('/lists/{list}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggleComplete'])->name('tasks.toggle');
});

require __DIR__.'/auth.php';
