<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->routes(function () {
            // ✅ This ensures routes in routes/web.php use the web middleware
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
