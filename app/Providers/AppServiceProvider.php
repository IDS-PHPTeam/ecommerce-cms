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
        // Set locale from session or default language setting
        $locale = Session::get('locale');
        
        if (!$locale) {
            // Check if multilingual is enabled
            $multilingual = Setting::get('multilingual', '0');
            if ($multilingual === '1') {
                $locale = Setting::get('default_language', 'en');
            } else {
                $locale = 'en';
            }
        }
        
        // Only set if it's a valid locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        }
    }
}
