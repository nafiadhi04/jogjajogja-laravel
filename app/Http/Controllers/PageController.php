<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Penginapan;
use App\Models\Wisata; // <-- Pastikan Model Wisata di-import
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
<<<<<<< HEAD
     * Menampilkan halaman daftar penginapan dengan filter.
=======
     * Tampilkan halaman utama/home dengan rekomendasi penginapan
     */
    public function index()
    {
        // Ambil penginapan yang berstatus 'diterima', memiliki is_rekomendasi = true, dan memiliki gambar.
        // Muat (eager load) relasi 'gambar' agar bisa diakses di view.
        $penginapan_list = Penginapan::where('status', 'diterima')
            ->where('is_rekomendasi', true)
            ->has('gambar')
            ->with(['gambar'])
            ->limit(12)
            ->get();
        
        return view('welcome', compact('penginapan_list'));
    }

    /**
     * Tampilkan daftar penginapan dengan filter
>>>>>>> 4310273791ca58fc2bce1538df1a44b617deacc1
     */
    public function listPenginapan(Request $request)
    {
        // Muat relasi 'fasilitas' dan 'gambar' di awal query untuk semua data.
        $query = Penginapan::query()->where('status', 'diterima')->with(['fasilitas', 'gambar']);

        // Filter penginapan berdasarkan input pencarian
        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });
        
        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->periode, fn($q, $periode) => $q->where('periode_harga', $periode));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga', '<=', $harga_max));

<<<<<<< HEAD
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = 'desc';
        if ($sortBy === 'harga')
            $sortOrder = 'asc';
        if ($sortBy === 'nama')
=======
        $allowedSorts = ['views', 'created_at', 'harga', 'nama'];
        
        $sortBy = $request->input('sort_by');
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'views';
        }

        $sortOrder = 'desc';
        if ($sortBy === 'harga' || $sortBy === 'nama') {
>>>>>>> 4310273791ca58fc2bce1538df1a44b617deacc1
            $sortOrder = 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $penginapan = $query->paginate(12)->withQueryString();

        $all_tipes = Penginapan::where('status', 'diterima')->select('tipe')->distinct()->pluck('tipe');
        $all_kotas = Penginapan::where('status', 'diterima')->select('kota')->distinct()->pluck('kota');
        $periode_options = ['Harian', 'Mingguan', 'Bulanan', 'Tahunan'];
        $all_fasilitas = Fasilitas::all();

        return view('frontend.penginapan.index', compact('penginapan', 'all_tipes', 'all_kotas', 'periode_options', 'all_fasilitas'));
    }

    /**
<<<<<<< HEAD
     * Menampilkan halaman detail penginapan.
=======
     * Tampilkan detail penginapan
>>>>>>> 4310273791ca58fc2bce1538df1a44b617deacc1
     */
    public function detailPenginapan(Penginapan $penginapan)
    {
        if ($penginapan->status !== 'diterima') {
            abort(404);
        }
        $penginapan->increment('views');

        // Ambil penginapan lain yang berstatus 'diterima' dan memiliki gambar.
        // Tidak lagi memfilter berdasarkan 'is_rekomendasi'
        // Muat (eager load) relasi 'gambar' pada data rekomendasi.
        $penginapan_rekomendasi = Penginapan::where('status', 'diterima')
            ->where('id', '!=', $penginapan->id)
            ->get();

        return view('frontend.penginapan.detail', [
            'penginapan' => $penginapan,
            'penginapan_rekomendasi' => $penginapan_rekomendasi,
        ]);
    }
<<<<<<< HEAD

    // ==========================================================
    // METHOD BARU UNTUK FITUR WISATA
    // ==========================================================

    /**
     * Menampilkan halaman daftar wisata dengan filter.
     */
    public function listWisata(Request $request)
    {
        $query = Wisata::query()->where('status', 'diterima')->with(['fasilitas', 'gambar']);

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });
        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga_tiket', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga_tiket', '<=', $harga_max));

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = 'desc';
        if ($sortBy === 'harga_tiket')
            $sortOrder = 'asc';
        if ($sortBy === 'nama')
            $sortOrder = 'asc';
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
            ->limit(4)
            ->get();

        return view('frontend.wisata.detail', compact('wisata', 'wisata_terkait'));
    }
}

=======
}
>>>>>>> 4310273791ca58fc2bce1538df1a44b617deacc1
