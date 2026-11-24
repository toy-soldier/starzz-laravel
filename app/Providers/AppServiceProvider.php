<?php

namespace App\Providers;

use App\Services\JwtService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JwtService::class, fn() => new JwtService(
            config('starzz.jwt_secret'),
            config('starzz.jwt_ttl'),
        ));
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
