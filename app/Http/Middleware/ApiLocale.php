<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->wantsJson()) {
            app()->setLocale($request->header('Accept-Language', config('app.locale')));
        }

        $response = $next($request);
        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }
}
