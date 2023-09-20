<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
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
        Vite::useScriptTagAttributes([
            'data-turbo-track' => 'reload', // Specify a value for the attribute...
            'async' => true, // Specify an attribute without a value...
            'integrity' => false, // Exclude an attribute that would otherwise be included...
        ]);
         
        Vite::useStyleTagAttributes([
            'data-turbo-track' => 'reload',
        ]);
        
        if (Schema::hasColumn('categories', 'name')){
            $category = Category::get(['name', 'slug']);
            View::share('categoriesGlobal', $category);
            View::share('categoriesFooter', count($category) > 0 ? array_chunk($category->toArray(), ceil(count($category)/2)) : null);
        }

        Model::preventLazyLoading(!app()->isProduction());

        Relation::morphMap([
            News::TABLE => News::class,
        ]);
    }
}
