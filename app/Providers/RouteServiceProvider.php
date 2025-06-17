<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            // Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Admin
            Route::middleware(['web', 'auth', 'admin']) // 👈
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }
}
