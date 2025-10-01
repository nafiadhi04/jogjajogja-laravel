<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Penginapan;
use App\Models\Wisata;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman daftar penginapan dengan filter.
     */
    public function listPenginapan(Request $request)
    {
        $query = Penginapan::query()
            ->where('status', 'diterima')
            ->with(['fasilitas', 'gambar']);

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });

        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->periode, fn($q, $periode) => $q->where('periode_harga', $periode));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga', '<=', $harga_max));

        // Whitelist untuk kolom sort agar aman
        $allowedSorts = ['created_at', 'harga', 'nama'];
        $sortBy = $request->input('sort_by', 'created_at');
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        // Aturan urutan: harga & nama ascending, lainnya descending
        $sortOrder = in_array($sortBy, ['harga', 'nama']) ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $penginapan = $query->paginate(12)->withQueryString();

        $all_tipes = Penginapan::where('status', 'diterima')->select('tipe')->distinct()->pluck('tipe');
        $all_kotas = Penginapan::where('status', 'diterima')->select('kota')->distinct()->pluck('kota');
        $periode_options = ['Harian', 'Mingguan', 'Bulanan', 'Tahunan'];
        $all_fasilitas = Fasilitas::all();

        return view('frontend.penginapan.index', compact(
            'penginapan',
            'all_tipes',
            'all_kotas',
            'periode_options',
            'all_fasilitas'
        ));
    }

    /**
     * Menampilkan halaman detail penginapan.
     */
    public function detailPenginapan(Penginapan $penginapan)
    {
        if ($penginapan->status !== 'diterima') {
            abort(404);
        }

        // Tambah views
        $penginapan->increment('views');

        // Rekomendasi: ambil 4 penginapan 'diterima' lain, acak, dan eager load relasi
        $penginapan_rekomendasi = Penginapan::where('status', 'diterima')
            ->where('id', '!=', $penginapan->id)
            ->with(['author', 'fasilitas', 'gambar'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.penginapan.detail', [
            'penginapan' => $penginapan,
            'penginapan_rekomendasi' => $penginapan_rekomendasi,
        ]);
    }

    // ==========================================================
    // METHOD UNTUK FITUR WISATA
    // ==========================================================

    /**
     * Menampilkan halaman daftar wisata dengan filter.
     */
    public function listWisata(Request $request)
    {
        $query = Wisata::query()
            ->where('status', 'diterima')
            ->with(['fasilitas', 'gambar']);

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });

        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga_tiket', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga_tiket', '<=', $harga_max));

        // Whitelist sort
        $allowedSorts = ['created_at', 'harga_tiket', 'nama'];
        $sortBy = $request->input('sort_by', 'created_at');
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortOrder = in_array($sortBy, ['harga_tiket', 'nama']) ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $wisatas = $query->paginate(12)->withQueryString();

        $all_tipes = Wisata::where('status', 'diterima')->select('tipe')->distinct()->pluck('tipe');
        $all_kotas = Wisata::where('status', 'diterima')->select('kota')->distinct()->pluck('kota');

        return view('frontend.wisata.index', compact('wisatas', 'all_tipes', 'all_kotas'));
    }

    /**
     * Menampilkan halaman detail wisata.
     */
    public function detailWisata(Wisata $wisata)
    {
        if ($wisata->status !== 'diterima') {
            abort(404);
        }

        $wisata->increment('views');

        $wisata_terkait = Wisata::where('status', 'diterima')
            ->where('kota', $wisata->kota)
            ->where('id', '!=', $wisata->id)
            ->with(['fasilitas', 'gambar'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.wisata.detail', compact('wisata', 'wisata_terkait'));
    }
}
