<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;

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
        // Locale is now set via SetLocale middleware after session is available
        // This ensures the session is properly initialized before reading locale
        
        // Share theme mode with all views
        view()->composer('*', function ($view) {
            $themeMode = Setting::get('theme_mode', 'light');
            $view->with('themeMode', $themeMode);
        });
    }
}
