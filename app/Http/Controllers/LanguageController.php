<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        if (!in_array($locale, ['en', 'am'])) abort(400);
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['language_preference' => $locale]);
        }
        return back();
    }
}
