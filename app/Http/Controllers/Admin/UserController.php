<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penginapan; // <-- Import model Penginapan
use App\Models\Wisata;    // <-- Import model Wisata
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $query->when($request->search, function ($q, $search) {
            return $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        });

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['admin', 'member', 'silver', 'gold', 'platinum', 'pending'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'member', 'silver', 'gold', 'platinum', 'pending'])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cari akun admin pertama sebagai author pengganti
        $adminUser = User::where('role', 'admin')->first();

        // Jika ada admin, alihkan kepemilikan artikel
        if ($adminUser) {
            Penginapan::where('user_id', $user->id)->update(['user_id' => $adminUser->id]);
            Wisata::where('user_id', $user->id)->update(['user_id' => $adminUser->id]);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus dan artikelnya telah dialihkan ke admin.');
    }

    /**
     * Remove multiple selected resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:users,id'],
        ]);

        $idsToDelete = $validated['ids'];

        $currentUserId = Auth::id();
        $idsToDelete = array_filter($idsToDelete, function ($id) use ($currentUserId) {
            return $id != $currentUserId;
        });

        if (count($idsToDelete) > 0) {
            // Cari akun admin pertama sebagai author pengganti
            $adminUser = User::where('role', 'admin')->first();

            // Alihkan kepemilikan artikel sebelum menghapus
            if ($adminUser) {
                Penginapan::whereIn('user_id', $idsToDelete)->update(['user_id' => $adminUser->id]);
                Wisata::whereIn('user_id', $idsToDelete)->update(['user_id' => $adminUser->id]);
            }

            User::whereIn('id', $idsToDelete)->delete();
            return redirect()->route('admin.users.index')->with('success', 'User yang dipilih berhasil dihapus dan artikelnya telah dialihkan ke admin.');
        }

        return redirect()->route('admin.users.index')->with('error', 'Tidak ada user yang dihapus. Anda tidak dapat menghapus akun Anda sendiri.');
    }
}