<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch the application's locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, string $locale)
    {
        // Validate the locale is supported
        if (!in_array($locale, ['en', 'fr', 'es'])) {
            return back()->with('error', 'The selected language is not supported.');
        }

        // Store the locale in the session
        session(['locale' => $locale]);

        // Store the locale in the user's preferences if they're logged in
        if ($request->user()) {
            $request->user()->update([
                'locale' => $locale
            ]);
        }

        return back()->with('success', 'Language changed successfully.');
    }
} 