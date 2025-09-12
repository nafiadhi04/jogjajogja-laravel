<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Penginapan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenginapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_penginapan = Penginapan::with('author')->latest()->paginate(10);
        return view('admin.penginapan.index', compact('all_penginapan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fasilitas = Fasilitas::all();
        return view('admin.penginapan.create', compact('fasilitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ... (Kode store Anda yang sudah benar ada di sini)
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:penginapans,nama',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|url',
            'harga' => 'required|integer',
            'periode_harga' => 'required|string',
            'tipe' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails_penginapan');

        $penginapan = Penginapan::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'harga' => $validated['harga'],
            'periode_harga' => $validated['periode_harga'],
            'tipe' => $validated['tipe'],
            'kota' => $validated['kota'],
            'thumbnail' => basename($thumbnailPath),
        ]);

        if ($request->has('fasilitas')) {
            $penginapan->fasilitas()->attach($validated['fasilitas']);
        }

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('public/galeri_penginapan');
                $penginapan->gambar()->create([
                    'path_gambar' => basename($gambarPath),
                ]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penginapan $penginapan)
    {
        $fasilitas = Fasilitas::all();
        return view('admin.penginapan.edit', compact('penginapan', 'fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penginapan $penginapan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:penginapans,nama,' . $penginapan->id,
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|url',
            'harga' => 'required|integer',
            'periode_harga' => 'required|string',
            'tipe' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $penginapan->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'harga' => $validated['harga'],
            'periode_harga' => $validated['periode_harga'],
            'tipe' => $validated['tipe'],
            'kota' => $validated['kota'],
        ]);

        if ($request->hasFile('thumbnail')) {
            Storage::delete('public/thumbnails_penginapan/' . $penginapan->thumbnail);
            $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails_penginapan');
            $penginapan->update(['thumbnail' => basename($thumbnailPath)]);
        }

        $penginapan->fasilitas()->sync($request->fasilitas);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('public/galeri_penginapan');
                $penginapan->gambar()->create([
                    'path_gambar' => basename($gambarPath),
                ]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penginapan $penginapan)
    {
        Storage::delete('public/thumbnails_penginapan/' . $penginapan->thumbnail);

        foreach ($penginapan->gambar as $gambar) {
            Storage::delete('public/galeri_penginapan/' . $gambar->path_gambar);
        }

        $penginapan->delete();

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil dihapus!');
    }
}

