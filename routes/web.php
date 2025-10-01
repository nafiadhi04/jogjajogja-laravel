<?php

use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PageController; // Pastikan PageController sudah di-import
use App\Models\User;
use App\Models\Penginapan;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == ROUTE UNTUK TAMPILAN PUBLIK / FRONTEND ==

// Perbaikan: Ubah rute home agar memanggil PageController@index
Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/penginapan', [PageController::class, 'listPenginapan'])->name('penginapan.list');
Route::get('/penginapan/{penginapan:slug}', [PageController::class, 'detailPenginapan'])->name('penginapan.detail');

// == ROUTE UNTUK USER YANG SUDAH LOGIN ==

Route::middleware(['auth'])->group(function () {
    // ... (kode di sini tidak perlu diubah) ...
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $totalPenginapan = 0;
        $totalUsers = 0;
        $penginapanVerifikasi = 0;
        $penginapanRevisi = 0;
        $penginapanDiterima = 0;

        if ($user->role === 'admin') {
            $totalPenginapan = Penginapan::count();
            $totalUsers = User::count();
        } else {
            $totalPenginapan = Penginapan::where('user_id', $user->id)->count();
            $penginapanVerifikasi = Penginapan::where('user_id', $user->id)->where('status', 'verifikasi')->count();
            $penginapanRevisi = Penginapan::where('user_id', $user->id)->where('status', 'revisi')->count();
            $penginapanDiterima = Penginapan::where('user_id', $user->id)->where('status', 'diterima')->count();
        }

        return view('dashboard', compact(
            'user',
            'totalPenginapan',
            'totalUsers',
            'penginapanVerifikasi',
            'penginapanRevisi',
            'penginapanDiterima'
        ));
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// == ROUTE KHUSUS UNTUK ADMIN PANEL ==

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ... (kode di sini juga tidak perlu diubah) ...
    // User management CRUD
    Route::resource('users', UserController::class);

    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::post('penginapan/destroy-multiple', [PenginapanController::class, 'destroyMultiple'])->name('penginapan.destroy.multiple');
    Route::patch('penginapan/{penginapan}/status', [PenginapanController::class, 'updateStatus'])->name('penginapan.status.update');
    Route::get('penginapan/gambar/{gambar}', [PenginapanController::class, 'destroyGambar'])->name('penginapan.gambar.destroy');

    // Resource route untuk kelola penginapan
    Route::resource('penginapan', PenginapanController::class)->except(['show']);
});

require __DIR__ . '/auth.php';