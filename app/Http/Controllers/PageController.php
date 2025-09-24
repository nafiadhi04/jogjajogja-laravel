<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Penginapan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function listPenginapan(Request $request)
    {
        $query = Penginapan::query()->where('status', 'diterima')->with(['fasilitas', 'gambar']);

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        });
        $query->when($request->tipe, fn($q, $tipe) => $q->where('tipe', $tipe));
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));
        $query->when($request->periode, fn($q, $periode) => $q->where('periode_harga', $periode));
        $query->when($request->harga_min, fn($q, $harga_min) => $q->where('harga', '>=', $harga_min));
        $query->when($request->harga_max, fn($q, $harga_max) => $q->where('harga', '<=', $harga_max));

        // Tentukan kolom yang diizinkan untuk pengurutan
        // Saya asumsikan 'rekomendasi' akan diurutkan berdasarkan 'views'
        $allowedSorts = ['views', 'created_at', 'harga', 'nama'];
        
        // Ambil input 'sort_by' dari URL, jika tidak valid, gunakan 'views' sebagai default
        $sortBy = $request->input('sort_by');
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'views';
        }

        $sortOrder = 'desc';
        // Atur sort order untuk harga dan nama menjadi ascending
        if ($sortBy === 'harga' || $sortBy === 'nama') {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $penginapan = $query->paginate(12)->withQueryString();

        $all_tipes = Penginapan::where('status', 'diterima')->select('tipe')->distinct()->pluck('tipe');
        $all_kotas = Penginapan::where('status', 'diterima')->select('kota')->distinct()->pluck('kota');
        $periode_options = ['Harian', 'Mingguan', 'Bulanan', 'Tahunan'];
        $all_fasilitas = Fasilitas::all();

        return view('frontend.penginapan.index', compact('penginapan', 'all_tipes', 'all_kotas', 'periode_options', 'all_fasilitas'));
    }

    public function detailPenginapan(Penginapan $penginapan)
    {
        if ($penginapan->status !== 'diterima') {
            abort(404);
        }
        $penginapan->increment('views');
        return view('frontend.penginapan.detail', ['penginapan' => $penginapan]);
    }
}
