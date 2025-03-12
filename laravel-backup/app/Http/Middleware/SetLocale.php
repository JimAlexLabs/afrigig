<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and has a locale preference
        if ($request->user() && $request->user()->locale) {
            App::setLocale($request->user()->locale);
        }
        // Check if locale is set in session
        elseif (session()->has('locale')) {
            App::setLocale(session('locale'));
        }
        // Check browser's preferred language
        else {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($browserLocale, ['en', 'fr', 'es'])) {
                App::setLocale($browserLocale);
                session(['locale' => $browserLocale]);
            } else {
                App::setLocale('en');
                session(['locale' => 'en']);
            }
        }

        return $next($request);
    }
} 