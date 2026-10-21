<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AssignCorrelationId
{
    /**
     * Handle an incoming request.
     * Assigne un identifiant de corrélation unique pour la traçabilité sans secrets.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());
        $request->headers->set('X-Correlation-ID', $requestId);

        $response = $next($request);
        $response->headers->set('X-Correlation-ID', $requestId);

        return $response;
    }
}
