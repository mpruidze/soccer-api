<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->wantsJson()) {
            $locale = $this->parseLocale($request->header('Accept-Language', config('app.locale')));

            try {
                app()->setLocale($locale);
            } catch (Exception) {
                app()->setLocale(config('app.locale'));
            }
        }

        $response = $next($request);
        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }

    private function parseLocale(string $acceptLanguage): string
    {
        $language = explode(',', $acceptLanguage)[0];

        return str_replace('-', '_', trim($language));
    }
}
