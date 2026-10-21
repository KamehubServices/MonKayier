<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isSuspended()) {
            return response()->json([
                'error' => [
                    'code' => 'ACCOUNT_SUSPENDED',
                    'message' => 'Ce compte a été suspendu. Veuillez contacter le support.',
                ],
                'meta' => [
                    'request_id' => $request->header('X-Correlation-ID', (string) \Illuminate\Support\Str::uuid()),
                ],
            ], 403);
        }

        return $next($request);
    }
}
