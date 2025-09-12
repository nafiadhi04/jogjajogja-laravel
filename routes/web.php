<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard untuk semua user
Route::get('/dashboard', function () {
    $user = auth()->user();
    $totalUsers = User::count();
    return view('dashboard', compact('user', 'totalUsers'));
})->middleware(['auth'])->name('dashboard');

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard
    Route::get('/', function () {
        $user = auth()->user();
        $totalUsers = User::count();
        $users = User::all();
        return view('admin.dashboard', compact('user', 'totalUsers', 'users'));
    })->name('dashboard');

    // User management CRUD
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
