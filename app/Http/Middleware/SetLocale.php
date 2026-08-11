<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', auth()->user()?->language_preference ?? 'en');
        app()->setLocale($locale);
        return $next($request);
    }
}
