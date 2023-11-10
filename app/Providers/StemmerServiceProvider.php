<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sastrawi\Stemmer\StemmerFactory;

class StemmerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('stemmer', function ($app) {
            return new StemmerFactory();
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
