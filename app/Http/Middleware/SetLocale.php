<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: session > cookie > default (ar)
        if (session()->has('locale')) {
            $locale = session('locale');
        } elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
            // Sync cookie value back to session
            session(['locale' => $locale]);
        } else {
            $locale = config('app.locale', 'ar');
        }

        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
