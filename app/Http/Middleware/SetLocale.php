<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale', session('locale', 'ar'));

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'ar';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return $next($request);
    }
}
