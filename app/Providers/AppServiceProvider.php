<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //for create_posts_table.php string('title')
        //limit the length of string data type
        Schema::defaultStringLength(191);

        //show category to the navbar in all views
        $all_view_categories = Category::all();
        View::share('all_view_categories', $all_view_categories);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
