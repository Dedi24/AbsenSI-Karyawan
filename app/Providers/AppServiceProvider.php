<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
        Schema::defaultStringLength(191);

        // Set locale Carbon ke Indonesia
        Carbon::setLocale('id');
        Carbon::setUtf8(true);

        // Set timezone ke Indonesia
        date_default_timezone_set('Asia/Jakarta');
    }
}
