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
        return view('dashboard', ['user' => $user]);
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
        return view('admin.dashboard', ['user' => auth()->user(), 'users' => User::all()]);
    })->name('dashboard');

    // User management CRUD
    Route::resource('users', UserController::class);

    // ==========================================================
    // ROUTE BARU UNTUK VERIFIKASI
    // ==========================================================
    Route::patch('penginapan/{penginapan}/status', [PenginapanController::class, 'updateStatus'])->name('penginapan.status.update');

    Route::get('penginapan/gambar/{gambar}/delete', [PenginapanController::class, 'destroyGambar'])->name('penginapan.gambar.destroy');
    Route::resource('penginapan', PenginapanController::class)->except(['show']);
});

require __DIR__ . '/auth.php';