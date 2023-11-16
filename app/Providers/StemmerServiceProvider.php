<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

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

        $this->app->singleton('stopWordRemover', function ($app) {
            return new StopWordRemoverFactory();
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
