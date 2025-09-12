<?php

use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PageController;

use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == ROUTE UNTUK TAMPILAN PUBLIK / FRONTEND ==

Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman daftar dan detail penginapan
Route::get('/penginapan', [PageController::class, 'listPenginapan'])->name('penginapan.list');
Route::get('/penginapan/{penginapan:slug}', [PageController::class, 'detailPenginapan'])->name('penginapan.detail');


// == ROUTE UNTUK USER YANG SUDAH LOGIN ==

Route::middleware(['auth'])->group(function () {
    // Dashboard untuk semua user
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $totalUsers = User::count();
        return view('dashboard', compact('user', 'totalUsers'));
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// == ROUTE KHUSUS UNTUK ADMIN PANEL ==

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

    // Route untuk kelola penginapan (CRUD di Admin Panel)
    // Sekarang tidak ada lagi konflik dengan route frontend
    Route::resource('penginapan', PenginapanController::class);

});

require __DIR__ . '/auth.php';
