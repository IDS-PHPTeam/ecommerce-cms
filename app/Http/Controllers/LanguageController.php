<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, $locale)
    {
        // Only allow 'en' and 'ar' locales
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        // Set locale in session
        Session::put('locale', $locale);
        Session::save();
        
        // Set locale for current request
        App::setLocale($locale);

        // Get the previous URL or default to login page
        $previousUrl = $request->headers->get('referer');
        
        // If no previous URL or if it's the same language switch URL, redirect to login or dashboard
        if (!$previousUrl || strpos($previousUrl, '/language/') !== false) {
            // Check if user is authenticated
            if (auth()->check()) {
                return redirect()->route('dashboard')->with('locale_changed', $locale);
            } else {
                return redirect()->route('login')->with('locale_changed', $locale);
            }
        }

        // Redirect back to the previous page
        return redirect($previousUrl)->with('locale_changed', $locale);
    }
}

