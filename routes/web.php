<?php

use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PageController;
use App\Models\User;
use App\Models\Penginapan; // <-- Pastikan model ini di-import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == ROUTE UNTUK TAMPILAN PUBLIK / FRONTEND ==

Route::get('/', function () {
    return view('welcome');
});

Route::get('/penginapan', [PageController::class, 'listPenginapan'])->name('penginapan.list');
Route::get('/penginapan/{penginapan:slug}', [PageController::class, 'detailPenginapan'])->name('penginapan.detail');


// == ROUTE UNTUK USER YANG SUDAH LOGIN ==

Route::middleware(['auth'])->group(function () {

    // ==========================================================
    // PERUBAHAN UTAMA ADA DI SINI
    // Route dashboard sekarang mengambil data statistik berdasarkan peran
    // ==========================================================
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Inisialisasi variabel
        $totalPenginapan = 0;
        $totalUsers = 0; // Variabel baru untuk total user

        // Variabel untuk member
        $penginapanVerifikasi = 0;
        $penginapanRevisi = 0;
        $penginapanDiterima = 0;

        // Logika untuk Admin
        if ($user->role === 'admin') {
            $totalPenginapan = Penginapan::count();
            $totalUsers = User::count(); // Menghitung semua user
        }
        // Logika untuk Member
        else {
            $totalPenginapan = Penginapan::where('user_id', $user->id)->count();
            $penginapanVerifikasi = Penginapan::where('user_id', $user->id)->where('status', 'verifikasi')->count();
            $penginapanRevisi = Penginapan::where('user_id', $user->id)->where('status', 'revisi')->count();
            $penginapanDiterima = Penginapan::where('user_id', $user->id)->where('status', 'diterima')->count();
        }

        // Kirim semua variabel ke view
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
    // User management CRUD
    Route::resource('users', UserController::class);

    // Route untuk kelola penginapan
    Route::patch('penginapan/{penginapan}/status', [PenginapanController::class, 'updateStatus'])->name('penginapan.status.update');
    Route::get('penginapan/gambar/{gambar}/delete', [PenginapanController::class, 'destroyGambar'])->name('penginapan.gambar.destroy');
    Route::resource('penginapan', PenginapanController::class)->except(['show']);
});

require __DIR__ . '/auth.php';

