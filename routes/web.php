<?php

use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PageController; // Pastikan PageController sudah di-import
use App\Models\User;
use App\Models\Penginapan;
use App\Models\Wisata;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == ROUTE UNTUK TAMPILAN PUBLIK / FRONTEND ==

// Perbaikan: Ubah rute home agar memanggil PageController@index
Route::get('/', [PageController::class, 'index'])->name('home');

// Route untuk Penginapan
Route::get('/penginapan', [PageController::class, 'listPenginapan'])->name('penginapan.list');
Route::get('/penginapan/{penginapan:slug}', [PageController::class, 'detailPenginapan'])->name('penginapan.detail');

// Route untuk Wisata
Route::get('/wisata', [PageController::class, 'listWisata'])->name('wisata.list');
Route::get('/wisata/{wisata:slug}', [PageController::class, 'detailWisata'])->name('wisata.detail');


// == ROUTE UNTUK USER YANG SUDAH LOGIN ==

Route::middleware(['auth'])->group(function () {
    // ... (kode di sini tidak perlu diubah) ...
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Inisialisasi variabel
        $totalPenginapan = 0;
        $totalWisata = 0; // <-- Variabel baru
        $totalUsers = 0;

        // Variabel penginapan untuk member
        $penginapanVerifikasi = 0;
        $penginapanRevisi = 0;
        $penginapanDiterima = 0;

        // Variabel wisata untuk member
        $wisataVerifikasi = 0;
        $wisataRevisi = 0;
        $wisataDiterima = 0;

        // Logika untuk Admin
        if ($user->role === 'admin') {
            $totalPenginapan = Penginapan::count();
            $totalWisata = Wisata::count(); // <-- Hitung total wisata
            $totalUsers = User::count();
        }
        // Logika untuk Member
        else {
            // Statistik Penginapan
            $totalPenginapan = Penginapan::where('user_id', $user->id)->count();
            $penginapanVerifikasi = Penginapan::where('user_id', $user->id)->where('status', 'verifikasi')->count();
            $penginapanRevisi = Penginapan::where('user_id', $user->id)->where('status', 'revisi')->count();
            $penginapanDiterima = Penginapan::where('user_id', $user->id)->where('status', 'diterima')->count();

            // Statistik Wisata
            $totalWisata = Wisata::where('user_id', $user->id)->count(); // <-- Hitung total wisata milik member
            $wisataVerifikasi = Wisata::where('user_id', $user->id)->where('status', 'verifikasi')->count();
            $wisataRevisi = Wisata::where('user_id', $user->id)->where('status', 'revisi')->count();
            $wisataDiterima = Wisata::where('user_id', $user->id)->where('status', 'diterima')->count();
        }

        return view('dashboard', compact(
            'user',
            'totalPenginapan',
            'totalWisata', // <-- Kirim data ke view
            'totalUsers',
            'penginapanVerifikasi',
            'penginapanRevisi',
            'penginapanDiterima',
            'wisataVerifikasi',
            'wisataRevisi',
            'wisataDiterima'
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

    // --- Penginapan Routes ---
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::post('penginapan/upload-image', [PenginapanController::class, 'uploadImage'])->name('penginapan.upload.image');
    Route::post('penginapan/destroy-multiple', [PenginapanController::class, 'destroyMultiple'])->name('penginapan.destroy.multiple');
    Route::patch('penginapan/{penginapan}/status', [PenginapanController::class, 'updateStatus'])->name('penginapan.status.update');
    Route::get('penginapan/gambar/{gambar}', [PenginapanController::class, 'destroyGambar'])->name('penginapan.gambar.destroy');
    Route::resource('penginapan', PenginapanController::class)
        ->except(['show'])
        ->parameters(['penginapan' => 'penginapan']);

    // --- Wisata Routes ---
    Route::post('wisata/destroy-multiple', [WisataController::class, 'destroyMultiple'])->name('wisata.destroy.multiple');
    Route::patch('wisata/{wisata}/status', [WisataController::class, 'updateStatus'])->name('wisata.status.update');
    Route::post('wisata/upload-image', [WisataController::class, 'uploadImage'])->name('wisata.upload.image');
    Route::get('wisata/gambar/{gambar}', [WisataController::class, 'destroyGambar'])->name('wisata.gambar.destroy');
    Route::resource('wisata', WisataController::class)
        ->except(['show'])
        ->parameters(['wisata' => 'wisata']);
});

require __DIR__ . '/auth.php';