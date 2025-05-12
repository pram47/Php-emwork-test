<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->app->getNamespace())
            ->group(base_path('routes/api.php'));
    }
}

