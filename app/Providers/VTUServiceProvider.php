<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\VTU\VTUProviderInterface;
use App\Services\VTU\PeyflexVTUService;
use App\Services\VTU\MockVTUService;

class VTUServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VTUProviderInterface::class, function ($app) {
            if (config('app.env') === 'production' || config('vtu.peyflex.api_key')) {
                return new PeyflexVTUService();
            }
            return new MockVTUService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
