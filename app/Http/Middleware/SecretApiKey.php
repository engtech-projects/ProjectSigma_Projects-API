<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecretApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientSecretKey = $request->bearerToken(); // Use Bearer token to skip setting up allowing new header name for secret key
        $secretKey = config('services.sigma.secret_key');
        if ($clientSecretKey === $secretKey) {
            return $next($request);
        }
        return new JsonResponse([
            'success' => false,
            'message' => 'Access denied. Wrong SECRET KEY',
        ], JsonResponse::HTTP_FORBIDDEN);
    }
}
