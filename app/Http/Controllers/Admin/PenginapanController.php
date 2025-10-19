<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Penginapan;
use App\Models\Wisata;
use App\Models\GambarPenginapan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PenginapanController extends Controller
{
    /**
     * Menampilkan daftar artikel dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Penginapan::query();

        // non-admin hanya melihat miliknya sendiri
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

        $all_penginapan = $query->with('author')->latest()->paginate(10)->withQueryString();

        $authors = User::whereIn('role', ['admin', 'member', 'silver', 'gold', 'platinum'])
            ->orderBy('name')
            ->get();

        return view('admin.penginapan.index', compact('all_penginapan', 'authors'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru dengan pengecekan batasan.
     */
    public function create()
    {
        $user = Auth::user();

        // jika bukan admin, periksa batas jumlah artikel berdasarkan role
        if ($user->role !== 'admin') {
            $limits = ['silver' => 1, 'gold' => 10, 'platinum' => 50, 'member' => 1];
            $limit = $limits[$user->role] ?? 0;

            $currentCount = Penginapan::where('user_id', $user->id)->count() + Wisata::where('user_id', $user->id)->count();

            if ($currentCount >= $limit) {
                return redirect()->back()
                    ->with('error', 'Anda telah mencapai batas maksimal ' . $limit . ' artikel untuk tipe akun Anda.');
            }
        }

        // pastikan fallback bila model fasilitas belum ada
        try {
            $fasilitas = Fasilitas::orderBy('nama')->get();
        } catch (\Throwable $e) {
            Log::warning('Fasilitas model not available: ' . $e->getMessage());
            $fasilitas = collect([]);
        }

        return view('admin.penginapan.create', compact('fasilitas'));
    }

    /**
     * Menyimpan artikel baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:penginapans,nama'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'harga' => ['required', 'numeric', 'min:0'],
            'periode_harga' => ['required', 'string'],
            'tipe' => ['required', 'string', Rule::in(['Villa', 'Hotel', 'Guest House', 'Homestay', 'Losmen'])],
            'kota' => ['required', 'string', Rule::in(['Kota Yogyakarta', 'Sleman', 'Bantul', 'Gunungkidul', 'Kulon Progo'])],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'gambar' => ['nullable', 'array'],
            'gambar.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
        ]);

        $penginapan = Penginapan::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'harga' => $validated['harga'],
            'periode_harga' => $validated['periode_harga'],
            'tipe' => $validated['tipe'],
            'kota' => $validated['kota'],
            'thumbnail' => $request->file('thumbnail')->store('thumbnails_penginapan', 'public'),
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
     * Menampilkan form edit.
     */
    public function edit(Penginapan $penginapan)
    {
        $user = Auth::user();
        if ($user->role === 'admin' || $penginapan->user_id === $user->id) {
            $fasilitas = Fasilitas::all();
            return view('admin.penginapan.edit', compact('penginapan', 'fasilitas'));
        }
        abort(403, 'AKSI TIDAK DIIZINKAN.');
    }

    /**
     * Mengupdate artikel.
     */
    public function update(Request $request, Penginapan $penginapan)
    {
        if (Auth::user()->role !== 'admin' && $penginapan->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('penginapans')->ignore($penginapan->id)],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'lokasi' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'harga' => ['required', 'numeric', 'min:0'],
            'periode_harga' => ['required', 'string'],
            'tipe' => ['required', 'string', Rule::in(['Villa', 'Hotel', 'Guest House', 'Homestay', 'Losmen'])],
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
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
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

        $penginapan->fasilitas()->sync($request->fasilitas ?? []);

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
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Simpan gambar ke folder 'deskripsi_images' di dalam public storage
            $path = $request->file('image')->store('deskripsi_images', 'public');

            // Dapatkan URL publik dari file yang baru disimpan
            $url = Storage::disk('public')->url($path);

            // Kembalikan URL dalam format JSON agar bisa dibaca oleh JavaScript
            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'Gagal mengupload gambar.'], 400);
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
    /**
     * Update status penginapan (menerima identifier yang bisa id atau slug)
     */
    public function updateStatus(Request $request, $identifier)
    {
        // validasi input minimal
        $data = $request->validate([
            'status' => 'required|string|in:diterima,verifikasi,revisi',
            'catatan_revisi' => 'nullable|string',
        ]);

        // cari model: coba numeric id dulu, lalu slug
        $penginapan = null;
        if (is_numeric($identifier)) {
            $penginapan = Penginapan::where('id', $identifier)->first();
        }
        if (!$penginapan) {
            $penginapan = Penginapan::where('slug', $identifier)->first();
        }
        if (!$penginapan) {
            return response()->json(['message' => "Penginapan tidak ditemukan untuk identifier: {$identifier}"], 404);
        }

        try {
            DB::beginTransaction();

            // minimal: assign status & catatan_revisi sesuai kebutuhan
            $penginapan->status = $data['status'];

            if ($data['status'] === 'revisi') {
                $penginapan->catatan_revisi = $data['catatan_revisi'] ?? null;
            } else {
                // clear revision note for non-revisi states
                $penginapan->catatan_revisi = null;
            }

            // jika butuh logic tambahan (published_at dsb), tambahkan dengan guard try/catch jika perlu

            $penginapan->save();

            DB::commit();

            // response JSON untuk request AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Status berhasil diperbarui.', 'status' => $penginapan->status]);
            }

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // log error lengkap -- pastikan facade Log sudah di-import
            Log::error('updateStatus failed for penginapan', [
                'identifier' => $identifier,
                'request' => $request->all(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Gagal menyimpan status. Periksa log server untuk detail.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan status. Periksa log server.');
        }
    }


    public function updateAuthor(Request $request, Penginapan $penginapan)
    {
        // Otorisasi hanya untuk admin
        if (!Gate::allows('admin')) {
            abort(403);
        }

        // Validasi input
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // Update author
        $penginapan->user_id = $validated['user_id'];
        $penginapan->save();

        return back()->with('success', 'Author artikel berhasil diperbarui.');
    }
}