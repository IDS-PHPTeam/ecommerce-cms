<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session
        $locale = Session::get('locale');
        
        // If no locale in session, check settings
        if (!$locale) {
            // Check if multilingual is enabled
            $multilingual = Setting::get('multilingual', '0');
            if ($multilingual === '1' || $multilingual == '1') {
                $locale = Setting::get('default_language', 'en');
            } else {
                $locale = 'en';
            }
        }
        
        // Only set if it's a valid locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}
