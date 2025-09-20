<?php

namespace App\Http\Controllers;

use App\Models\Penginapan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function listPenginapan(Request $request)
    {
        // DEBUG: Uncomment untuk debugging
        // dd($request->all());
        
        $query = Penginapan::with(['fasilitas', 'gambar', 'author']);

        // Filter berdasarkan tipe penginapan
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan range harga
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Filter berdasarkan kota
        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        // Filter berdasarkan periode - PERBAIKAN
        if ($request->filled('periode')) {
            $query->where('periode_harga', $request->periode);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $penginapan = $query->paginate(12)->withQueryString();

        // Data untuk dropdown - PERBAIKAN
        $all_tipes = Penginapan::distinct()->pluck('tipe')->filter()->sort();
        $all_kotas = Penginapan::distinct()->pluck('kota')->filter()->sort();
        $all_fasilitas = Fasilitas::orderBy('nama')->get();
        
        // Periode options sesuai gambar
        $periode_options = [
            'Harian',
            'Mingguan', 
            'Bulanan',
            'Tahunan'
        ];
        
        return view('Frontend.penginapan.index', compact(
            'penginapan',
            'all_tipes', 
            'all_kotas', 
            'all_fasilitas',
            'periode_options'
        ));
    }

    public function detailPenginapan(Penginapan $penginapan)
    {
        // Tambahkan Eager Loading di sini untuk memastikan relasi selalu dimuat.
        // Ini adalah solusi utama untuk mengatasi error yang Anda alami.
        $penginapan->load('fasilitas', 'gambar', 'author'); 
        
        // Tambah 1 ke kolom views
        $penginapan->increment('views');

        // Ambil penginapan terkait (berdasarkan kota yang sama, exclude yang sedang dilihat)
        $penginapan_terkait = Penginapan::with(['fasilitas', 'gambar'])
                                       ->where('kota', $penginapan->kota)
                                       ->where('id', '!=', $penginapan->id)
                                       ->limit(4)
                                       ->get();

        return view('Frontend.penginapan.detail', compact('penginapan', 'penginapan_terkait'));
    }
}