<?php

namespace App\Providers;

use App\Models\User; // <-- Pastikan User di-import
use Illuminate\Support\Facades\Gate; // <-- Pastikan Gate di-import
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ==========================================================
        // TAMBAHKAN KODE DI BAWAH INI
        // ==========================================================

        // Mendefinisikan Gate bernama 'admin'.
        // Gate ini akan mengembalikan 'true' hanya jika
        // kolom 'role' pada user yang sedang login adalah 'admin'.
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}