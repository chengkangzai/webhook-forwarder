<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGreenApiWebhookMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('services.green-api.secret')) {
            return $next($request);
        }

        if (! $request->bearerToken()) {
            return new Response('Unauthorized', 401);
        }

        if ($request->bearerToken() !== config('services.green-api.secret')) {
            return new Response('Unauthorized', 401);
        }

        return $next($request);
    }
}
