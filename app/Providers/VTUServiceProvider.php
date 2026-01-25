<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\VTU\VTUProviderInterface;
use App\Services\VTU\EpinsVTUService;
use App\Services\VTU\MockVTUService;

class VTUServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VTUProviderInterface::class, function ($app) {
            if (config('app.env') === 'production') {
                return new EpinsVTUService();
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
