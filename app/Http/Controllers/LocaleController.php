<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            session(['locale' => $locale]);
            
            // Also set a long-lived cookie (1 year) for persistence across sessions
            return back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
        }

        return back();
    }
}
