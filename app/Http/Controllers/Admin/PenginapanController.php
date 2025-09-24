<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Penginapan;
use App\Models\GambarPenginapan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PenginapanController extends Controller
{
    /**
     * Menampilkan daftar artikel dengan filter pencarian tunggal.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Penginapan::query();

        if ($user->role === 'member') {
            $query->where('user_id', $user->id);
        }

        // Logika filter pencarian tunggal
        $query->when($request->search, function ($q, $search) use ($user) {
            return $q->where(function ($subQuery) use ($search, $user) {
                $subQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");

                // Hanya admin yang bisa mencari berdasarkan nama author
                if ($user->role === 'admin') {
                    $subQuery->orWhereHas('author', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'like', "%{$search}%");
                    });
                }
            });
        });

        $all_penginapan = $query->with('author')->latest()->paginate(10)->withQueryString();

        return view('admin.penginapan.index', compact('all_penginapan'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        $fasilitas = Fasilitas::all();
        return view('admin.penginapan.create', compact('fasilitas'));
    }

    /**
     * Menyimpan artikel baru dengan status default 'verifikasi'.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:penginapans,nama'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'url'],
            'harga' => ['required', 'integer', 'min:0'],
            'periode_harga' => ['required', 'string'],
            'tipe' => ['required', 'string', Rule::in(['Villa', 'Hotel'])],
            'kota' => ['required', 'string', Rule::in(['Kota Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
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
            'status' => 'verifikasi',
        ]);

        if ($request->has('fasilitas')) {
            $penginapan->fasilitas()->attach($validated['fasilitas']);
        }

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('galeri_penginapan', 'public');
                $penginapan->gambar()->create(['path_gambar' => $gambarPath]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel berhasil dikirim dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan form edit dengan pengecekan hak akses.
     */
    public function edit(Penginapan $penginapan)
    {
        $user = Auth::user();
        if ($user->role === 'admin' || ($penginapan->user_id === $user->id && $penginapan->status === 'revisi')) {
            $fasilitas = Fasilitas::all();
            return view('admin.penginapan.edit', compact('penginapan', 'fasilitas'));
        }
        abort(403, 'AKSI TIDAK DIIZINKAN.');
    }

    /**
     * Mengupdate artikel dan mengembalikan statusnya ke 'verifikasi'.
     */
    public function update(Request $request, Penginapan $penginapan)
    {
        if (Auth::user()->role !== 'admin' && $penginapan->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('penginapans')->ignore($penginapan->id)],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'url'],
            'harga' => ['required', 'integer', 'min:0'],
            'periode_harga' => ['required', 'string'],
            'tipe' => ['required', 'string', Rule::in(['Villa', 'Hotel'])],
            'kota' => ['required', 'string', Rule::in(['Kota Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'gambar' => ['nullable', 'array'],
            'gambar.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
        ]);

        $penginapan->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'periode_harga' => $request->periode_harga,
            'tipe' => $request->tipe,
            'kota' => $request->kota,
            'status' => 'verifikasi',
            'catatan_revisi' => null,
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($penginapan->thumbnail) {
                Storage::disk('public')->delete($penginapan->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails_penginapan', 'public');
            $penginapan->update(['thumbnail' => $thumbnailPath]);
        }

        $penginapan->fasilitas()->sync($request->fasilitas);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('galeri_penginapan', 'public');
                $penginapan->gambar()->create(['path_gambar' => $gambarPath]);
            }
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel berhasil diperbarui dan dikirim ulang untuk verifikasi.');
    }

    /**
     * Menghapus satu artikel.
     */
    public function destroy(Penginapan $penginapan)
    {
        if (Auth::user()->role !== 'admin' && $penginapan->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        if ($penginapan->thumbnail) {
            Storage::disk('public')->delete($penginapan->thumbnail);
        }

        foreach ($penginapan->gambar as $gambar) {
            Storage::disk('public')->delete($gambar->path_gambar);
        }

        $penginapan->fasilitas()->detach();
        $penginapan->gambar()->delete();
        $penginapan->delete();

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel penginapan berhasil dihapus!');
    }

    /**
     * Menghapus beberapa artikel yang dipilih secara massal.
     */
    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:penginapans,id'],
        ]);

        $user = Auth::user();
        $query = Penginapan::whereIn('id', $validated['ids']);

        if ($user->role === 'member') {
            $query->where('user_id', $user->id);
        }

        $penginapansToDelete = $query->get();

        foreach ($penginapansToDelete as $penginapan) {
            if ($penginapan->thumbnail) {
                Storage::disk('public')->delete($penginapan->thumbnail);
            }
            foreach ($penginapan->gambar as $gambar) {
                Storage::disk('public')->delete($gambar->path_gambar);
            }
            $penginapan->delete();
        }

        return redirect()->route('admin.penginapan.index')
            ->with('success', 'Artikel yang dipilih berhasil dihapus.');
    }

    /**
     * Menghapus satu gambar galeri.
     */
    public function destroyGambar(GambarPenginapan $gambar)
    {
        if (Auth::user()->role !== 'admin' && $gambar->penginapan->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        Storage::disk('public')->delete($gambar->path_gambar);
        $gambar->delete();

        return back()->with('success_gambar', 'Gambar galeri berhasil dihapus!');
    }

    /**
     * Mengupdate status artikel (terima atau revisi).
     */
    public function updateStatus(Request $request, Penginapan $penginapan)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['diterima', 'revisi'])],
            'catatan_revisi' => ['nullable', 'string', 'max:1000', 'required_if:status,revisi'],
        ]);

        $penginapan->status = $validated['status'];
        $penginapan->catatan_revisi = $validated['catatan_revisi'] ?? null;
        $penginapan->save();

        return back()->with('success', 'Status artikel berhasil diperbarui.');
    }
}

