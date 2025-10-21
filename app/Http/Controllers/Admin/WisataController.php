<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\GambarWisata;
use App\Models\User;
use App\Models\Wisata;
use App\Models\Penginapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class WisataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Wisata::query();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $query->when($request->search, function ($q, $search) use ($user) {
            return $q->where(function ($subQuery) use ($search, $user) {
                $subQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");

                if ($user->role === 'admin') {
                    $subQuery->orWhereHas('author', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'like', "%{$search}%");
                    });
                }
            });
        });

        $all_wisata = $query->with('author')->latest()->paginate(10)->withQueryString();

        $authors = User::whereIn('role', ['admin', 'member', 'silver', 'gold', 'platinum'])->orderBy('name')->get();

        return view('admin.wisata.index.index', compact('all_wisata', 'authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $limits = ['silver' => 1, 'gold' => 10, 'platinum' => 50, 'member' => 1];
            $limit = $limits[$user->role] ?? 0;

            $currentCount = Wisata::where('user_id', $user->id)->count() + Penginapan::where('user_id', $user->id)->count();

            if ($currentCount >= $limit) {
                return back()->with('error', 'Anda telah mencapai batas maksimal ' . $limit . ' artikel untuk tipe akun Anda.');
            }
        }

        $fasilitas = Fasilitas::all();
        return view('admin.wisata.create.index', compact('fasilitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:wisatas,nama'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'harga_tiket' => ['required', 'numeric', 'min:0'],
            'tipe' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', Rule::in(['Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'gambar' => ['nullable', 'array'],
            'gambar.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
        ]);

        $wisata = Wisata::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'harga_tiket' => $validated['harga_tiket'],
            'tipe' => $validated['tipe'],
            'kota' => $validated['kota'],
            'thumbnail' => $request->file('thumbnail')->store('thumbnails_wisata', 'public'),
            'status' => 'verifikasi',
        ]);

        if ($request->has('fasilitas')) {
            $wisata->fasilitas()->attach($validated['fasilitas']);
        }

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('galeri_wisata', 'public');
                $wisata->gambar()->create(['path_gambar' => $gambarPath]);
            }
        }

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Artikel wisata berhasil dikirim dan sedang menunggu verifikasi.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wisata $wisata)
    {
        $user = Auth::user();
        if ($user->role === 'admin' || $wisata->user_id === $user->id) {
            $fasilitas = Fasilitas::all();
            return view('admin.wisata.edit.index', compact('wisata', 'fasilitas'));
        }
        abort(403, 'AKSI TIDAK DIIZINKAN.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wisata $wisata)
    {
        if (Auth::user()->role !== 'admin' && $wisata->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('wisatas')->ignore($wisata->id)],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'harga_tiket' => ['required', 'numeric', 'min:0'],
            'tipe' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', Rule::in(['Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'gambar' => ['nullable', 'array'],
            'gambar.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
        ]);

        $wisata->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'harga_tiket' => $request->harga_tiket,
            'tipe' => $request->tipe,
            'kota' => $request->kota,
            'status' => 'verifikasi',
            'catatan_revisi' => null,
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($wisata->thumbnail) {
                Storage::disk('public')->delete($wisata->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails_wisata', 'public');
            $wisata->update(['thumbnail' => $thumbnailPath]);
        }

        $wisata->fasilitas()->sync($request->fasilitas ?? []);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPath = $file->store('galeri_wisata', 'public');
                $wisata->gambar()->create(['path_gambar' => $gambarPath]);
            }
        }

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Artikel wisata berhasil diperbarui dan dikirim ulang untuk verifikasi.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wisata $wisata)
    {
        if (Auth::user()->role !== 'admin' && $wisata->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        if ($wisata->thumbnail) {
            Storage::disk('public')->delete($wisata->thumbnail);
        }

        foreach ($wisata->gambar as $gambar) {
            Storage::disk('public')->delete($gambar->path_gambar);
        }

        $wisata->fasilitas()->detach();
        $wisata->gambar()->delete();
        $wisata->delete();

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Artikel wisata berhasil dihapus!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Simpan gambar ke folder 'deskripsi_images_wisata'
            $path = $request->file('image')->store('deskripsi_images_wisata', 'public');

            $url = Storage::disk('public')->url($path);

            // Kembalikan URL dalam format JSON agar bisa dibaca oleh JavaScript
            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'Gagal mengupload gambar.'], 400);
    }


    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:wisatas,id'],
        ]);

        $user = Auth::user();
        $query = Wisata::whereIn('id', $validated['ids']);

        if ($user->role === 'member') {
            $query->where('user_id', $user->id);
        }

        $wisatasToDelete = $query->get();

        foreach ($wisatasToDelete as $wisata) {
            if ($wisata->thumbnail) {
                Storage::disk('public')->delete($wisata->thumbnail);
            }
            foreach ($wisata->gambar as $gambar) {
                Storage::disk('public')->delete($gambar->path_gambar);
            }
            $wisata->delete();
        }

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Artikel yang dipilih berhasil dihapus.');
    }

    public function destroyGambar(GambarWisata $gambar)
    {
        if (Auth::user()->role !== 'admin' && $gambar->wisata->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        Storage::disk('public')->delete($gambar->path_gambar);
        $gambar->delete();

        return back()->with('success_gambar', 'Gambar galeri berhasil dihapus!');
    }

    public function updateStatus(Request $request, $identifier)
    {
        $data = $request->validate([
            'status' => 'required|string|in:diterima,verifikasi,revisi',
            'catatan_revisi' => 'nullable|string',
        ]);

        // Resolve model: 3 kemungkinan
        // 1) $identifier sudah instance Wisata (happens if route-model-binding used and method typed differently)
        if ($identifier instanceof Wisata) {
            $wisata = $identifier;
        } else {
            $wisata = null;

            // 2) numeric id
            if (is_numeric($identifier)) {
                $wisata = Wisata::where('id', $identifier)->first();
            }

            // 3) try by slug if not found by id
            if (!$wisata) {
                $wisata = Wisata::where('slug', $identifier)->first();
            }
        }

        if (!$wisata) {
            return response()->json(['message' => "Wisata tidak ditemukan untuk identifier: {$identifier}"], 404);
        }

        try {
            DB::beginTransaction();

            // set status & catatan_revisi sesuai keadaan
            $wisata->status = $data['status'];

            if ($data['status'] === 'revisi') {
                $wisata->catatan_revisi = $data['catatan_revisi'] ?? null;
            } else {
                // clear revision note for non-revisi
                $wisata->catatan_revisi = null;
            }

            $wisata->save();

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Status berhasil diperbarui.', 'status' => $wisata->status]);
            }

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Pastikan facade Log sudah di-import di bagian atas file:
            // use Illuminate\Support\Facades\Log;
            Log::error('updateStatus failed for wisata', [
                'identifier' => $identifier,
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Gagal menyimpan status. Periksa log server.', 'error' => $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan status. Periksa log server.');
        }
    }
    public function updateAuthor(Request $request, Wisata $wisata)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $wisata->user_id = $validated['user_id'];
        $wisata->save();

        return back()->with('success', 'Author artikel berhasil diperbarui.');
    }
}
