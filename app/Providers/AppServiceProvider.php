<?php

namespace App\Providers;

use App\Models\SKU;
use App\Observers\SkuObserver;
use Illuminate\Support\ServiceProvider;

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
        Sku::observe(SkuObserver::class);
    }
}
