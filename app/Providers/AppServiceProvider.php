<?php

namespace App\Providers;

use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // View Composer: kirim $pendingCount ke layout sekali, bukan query di setiap render
        View::composer('layouts.app', function ($view) {
            $pendingCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->isAdmin()) {
                    // Admin lihat semua pending
                    $pendingCount = Pendaftaran::where('status', 'pending')->count();
                } elseif ($user->isTrainer()) {
                    // Trainer hanya pending di pelatihan yang dia ajar
                    $pendingCount = Pendaftaran::where('status', 'pending')
                        ->whereHas('pelatihan.trainers', fn($q) => $q->where('users.id', $user->id))
                        ->count();
                }
            }

            $view->with('pendingCount', $pendingCount);
        });
    }
}
