<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        if (Schema::hasColumn('categories', 'name')){
            $category = Category::get(['name', 'slug']);
            View::share('categoriesGlobal', $category);
            View::share('categoriesFooter', count($category) > 0 ? array_chunk($category->toArray(), ceil(count($category)/2)) : null);
        }
    }
}
