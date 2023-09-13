<?php

namespace App\Providers;

use App\Http\interfaces\CustomTokenInterface;
use App\Services\Auth\CustomToken;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CustomTokenInterface::class,
            CustomToken::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
