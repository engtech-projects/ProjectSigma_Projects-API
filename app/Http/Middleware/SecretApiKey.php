<?php

namespace App\Http\Middleware;

use Closure;
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
        $clientApiKey = $request->header('x-api-key');
        
        $apiKey = config('services.sigma.secret_key');
        
        $isAllowed = ($clientApiKey === $apiKey);

        if ( !$isAllowed ) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);

        }

        return $next($request);
    }
}
