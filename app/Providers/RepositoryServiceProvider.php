<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Contracts
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use App\Repositories\Contracts\DosenRepositoryInterface;
use App\Repositories\Contracts\MataKuliahRepositoryInterface;
use App\Repositories\Contracts\RuanganRepositoryInterface;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use App\Repositories\Contracts\RencanaStudiRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

// Eloquent Implementations
use App\Repositories\Eloquent\MahasiswaRepository;
use App\Repositories\Eloquent\DosenRepository;
use App\Repositories\Eloquent\MataKuliahRepository;
use App\Repositories\Eloquent\RuanganRepository;
use App\Repositories\Eloquent\JadwalRepository;
use App\Repositories\Eloquent\RencanaStudiRepository;
use App\Repositories\Eloquent\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MahasiswaRepositoryInterface::class, MahasiswaRepository::class);
        $this->app->bind(DosenRepositoryInterface::class, DosenRepository::class);
        $this->app->bind(MataKuliahRepositoryInterface::class, MataKuliahRepository::class);
        $this->app->bind(RuanganRepositoryInterface::class, RuanganRepository::class);
        $this->app->bind(JadwalRepositoryInterface::class, JadwalRepository::class);
        $this->app->bind(RencanaStudiRepositoryInterface::class, RencanaStudiRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
