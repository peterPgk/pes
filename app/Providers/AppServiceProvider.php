<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	//This will fix 1071 error for MariaDB and MySQL prior to 5.7.7
	    //https://laravel-news.com/laravel-5-4-key-too-long-error
	    Schema::defaultStringLength(191);
    }
}
