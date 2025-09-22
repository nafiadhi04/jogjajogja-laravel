<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Penginapan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function listPenginapan(Request $request)
    {
        // ==========================================================
        // PERUBAHAN UTAMA DI SINI:
        // Query sekarang HANYA mengambil artikel dengan status 'diterima'
        // ==========================================================
        $query = Penginapan::query()->where('status', 'diterima')->with(['fasilitas', 'gambar']);

        // (Sisa logika filter dan sorting tidak berubah)
        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });
        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->periode, fn($q, $periode) => $q->where('periode_harga', $periode));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga', '<=', $harga_max));

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = 'desc';
        if ($sortBy === 'harga')
            $sortOrder = 'asc';
        if ($sortBy === 'nama')
            $sortOrder = 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $penginapan = $query->paginate(12)->withQueryString();

        // Data untuk dropdown filter juga hanya dari artikel yang sudah diterima
        $all_tipes = Penginapan::where('status', 'diterima')->select('tipe')->distinct()->pluck('tipe');
        $all_kotas = Penginapan::where('status', 'diterima')->select('kota')->distinct()->pluck('kota');
        $periode_options = ['Harian', 'Mingguan', 'Bulanan', 'Tahunan'];
        $all_fasilitas = Fasilitas::all();

        return view('frontend.penginapan.index', compact('penginapan', 'all_tipes', 'all_kotas', 'periode_options', 'all_fasilitas'));
    }

    public function detailPenginapan(Penginapan $penginapan)
    {
        // Memastikan hanya artikel yang diterima yang bisa diakses publik
        if ($penginapan->status !== 'diterima') {
            abort(404); // Tampilkan halaman tidak ditemukan
        }
        $penginapan->increment('views');
        return view('frontend.penginapan.detail', ['penginapan' => $penginapan]);
    }
}