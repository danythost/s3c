<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Listeners\CreateVirtualAccountForUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();

        User::observe(UserObserver::class);

        Event::listen(
            Registered::class,
            CreateVirtualAccountForUser::class
        );

        Event::listen(Login::class, function ($event) {
            LoginHistory::create([
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'username' => $event->user->username,
                'role' => $event->user->role ?? 'user',
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'success',
            ]);
        });

        Event::listen(Failed::class, function ($event) {
            LoginHistory::create([
                'user_id' => $event->user ? $event->user->id : null,
                'email' => $event->credentials['email'] ?? null,
                'username' => $event->credentials['username'] ?? 'unknown',
                'role' => $event->user ? ($event->user->role ?? 'user') : null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'failed',
            ]);
        });

        // Rate Limiters
        RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('admin_login', function (\Illuminate\Http\Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
