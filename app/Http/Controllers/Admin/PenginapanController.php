<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Penginapan;
use App\Models\GambarPenginapan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // <-- Import Rule

class PenginapanController extends Controller
{
    // ... (method index, create, edit tidak berubah)
    public function index()
    {
        $all_penginapan = Penginapan::with('author')->latest()->paginate(10);
        return view('admin.penginapan.index', compact('all_penginapan'));
    }

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
        // ==========================================================
        // VALIDASI DIPERBARUI DI SINI
        // ==========================================================
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'], // Batasan 100 karakter
            'deskripsi' => ['required', 'string', 'max:5000'], // Batasan 5000 karakter
            'lokasi' => ['nullable', 'url'],
            'harga' => ['required', 'integer', 'min:0'], // Memastikan hanya angka integer positif
            'periode_harga' => ['required', 'string'],
            // Memastikan tipe adalah salah satu dari opsi yang valid
            'tipe' => ['required', 'string', Rule::in(['Villa', 'Hotel'])],
            // Memastikan kota adalah salah satu dari opsi yang valid
            'kota' => ['required', 'string', Rule::in(['Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'gambar' => ['nullable', 'array'],
            'gambar.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
        ]);

        $slug = Str::slug($validated['nama']);
        $originalSlug = $slug;
        $count = 1;
        while (Penginapan::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }   

        $thumbnailPath = $request->file('thumbnail')->store('thumbnails_penginapan', 'public');

        $penginapan = Penginapan::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'slug' => $slug,
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'harga' => $validated['harga'],
            'periode_harga' => $validated['periode_harga'],
            'tipe' => $validated['tipe'],
            'kota' => $validated['kota'],
            'thumbnail' => $thumbnailPath,
        ]);

        if ($request->has('fasilitas')) {
            $penginapan->fasilitas()->attach($validated['fasilitas']);
        }

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('galeri_penginapan', 'public');
                $penginapan->gambar()->create([
                    'path_gambar' => $gambarPath,
                ]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil ditambahkan!');
    }

    public function edit(Penginapan $penginapan)
    {
        $fasilitas = Fasilitas::all();
        return view('admin.penginapan.edit', compact('penginapan', 'fasilitas'));
    }

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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:20480',
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
            if ($penginapan->thumbnail) {
                Storage::disk('public')->delete($penginapan->thumbnail);
            }
            // Perbaikan juga diterapkan di sini
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails_penginapan', 'public');
            $penginapan->update(['thumbnail' => $thumbnailPath]);
        }

        $penginapan->fasilitas()->sync($request->fasilitas);

        if ($request->hasFile('gambar')) {
            foreach ($penginapan->gambar as $gambar) {
                Storage::disk('public')->delete($gambar->path_gambar);
            }
            $penginapan->gambar()->delete();

            foreach ($request->file('gambar') as $file) {
                // Perbaikan juga diterapkan di sini
                $gambarPath = $file->store('galeri_penginapan', 'public');
                $penginapan->gambar()->create([
                    'path_gambar' => $gambarPath,
                ]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil diperbarui!');
    }

    public function destroy(Penginapan $penginapan)
    {
        if ($penginapan->thumbnail) {
            Storage::disk('public')->delete($penginapan->thumbnail);
        }
        foreach ($penginapan->gambar as $gambar) {
            Storage::disk('public')->delete($gambar->path_gambar);
        }
        $penginapan->delete();

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil dihapus!');
    }
}

