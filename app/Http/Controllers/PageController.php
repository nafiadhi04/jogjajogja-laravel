<?php

namespace App\Http\Controllers;

use App\Models\Penginapan; // <-- Jangan lupa import modelnya
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function listPenginapan()
    {
        // Ambil semua data penginapan, urutkan dari yang paling baru.
        // Gunakan paginate() agar data tidak dimuat semua sekaligus, ini baik untuk performa.
        // Angka 9 berarti 9 item per halaman.
        $all_penginapan = Penginapan::latest()->paginate(9);

        // Kirim data tersebut ke view
        return view('frontend.penginapan.index', [
            'all_penginapan' => $all_penginapan
        ]);
    }

    /**
     * Menampilkan detail satu penginapan berdasarkan slug.
     * Menggunakan Route Model Binding untuk otomatis mencari data.
     *
     * @param  \App\Models\Penginapan  $penginapan
     * @return \Illuminate\View\View
     */
    public function detailPenginapan(Penginapan $penginapan)
    {
        // 1. Tambah jumlah view setiap kali halaman ini dibuka
        $penginapan->increment('views');

        // 2. Kirim data penginapan yang sudah ditemukan oleh Laravel ke view
        return view('frontend.penginapan.detail', [
            'penginapan' => $penginapan
        ]);
    }
}
