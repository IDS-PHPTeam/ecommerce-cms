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
        
        // Set locale for current request
        App::setLocale($locale);

        // Get the previous URL
        $previousUrl = $request->headers->get('referer');
        $currentUrl = url()->current();
        
        // Check if previous URL is a language switch URL or same as current
        $isLanguageSwitchUrl = $previousUrl && (strpos($previousUrl, '/language/') !== false);
        $isSameUrl = $previousUrl === $currentUrl;
        
        // If previous URL is invalid, language switch URL, or same as current, determine redirect target
        if (!$previousUrl || $isLanguageSwitchUrl || $isSameUrl) {
            // Check if user is authenticated
            if (auth()->check()) {
                return redirect()->route('dashboard')->with('locale_changed', $locale);
            } else {
                return redirect()->route('login')->with('locale_changed', $locale);
            }
        }
        
        // Check if previous URL was the login page
        $isLoginPage = strpos($previousUrl, '/login') !== false;
        if ($isLoginPage && !auth()->check()) {
            return redirect()->route('login')->with('locale_changed', $locale);
        }

        // Redirect back to the previous page
        return redirect($previousUrl)->with('locale_changed', $locale);
    }
}

