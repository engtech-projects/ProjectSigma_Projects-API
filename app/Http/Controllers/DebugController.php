<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    /**
     * Test endpoint that uses secret_api middleware.
     */
    public function secretTest(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Secret API middleware authentication successful!',
            'timestamp' => now(),
            'headers_received' => $request->headers->all(),
            'bearer_token' => $request->bearerToken(),
        ]);
    }

    /**
     * Test endpoint without any middleware.
     */
    public function noAuthTest(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Controller is working (no middleware applied)',
            'timestamp' => now(),
        ]);
    }

    /**
     * Show current configuration values.
     */
    public function showConfig(Request $request): JsonResponse
    {
        $secretKey = config('services.sigma.secret_key');

        return response()->json([
            'config_status' => [
                'sigma_secret_key_set'    => !empty($secretKey),
                'sigma_secret_key_length' => strlen($secretKey ?? ''),
                'sigma_secret_key_preview'=> $secretKey ? substr($secretKey, 0, 4) . '...' : 'NOT SET',
                'services_config_exists'  => config('services.sigma') !== null,
            ],
            'env_status' => [
                'app_env'   => config('app.env'),
                'app_debug' => config('app.debug'),
            ],
        ]);
    }

    /**
     * Show all request headers for debugging.
     */
    public function showHeaders(Request $request): JsonResponse
    {
        return response()->json([
            'all_headers'           => $request->headers->all(),
            'authorization_header'  => $request->header('Authorization'),
            'bearer_token'          => $request->bearerToken(),
            'method'                => $request->method(),
            'url'                   => $request->fullUrl(),
        ]);
    }

    /**
     * Manually test the middleware logic.
     */
    public function manualAuthTest(Request $request): JsonResponse
    {
        $clientSecretKey = $request->bearerToken();
        $serverSecretKey = config('services.sigma.secret_key');

        $result = [
            'client_secret' => [
                'provided'   => !empty($clientSecretKey),
                'length'     => strlen($clientSecretKey ?? ''),
                'preview'    => $clientSecretKey ? substr($clientSecretKey, 0, 4) . '...' : 'NOT PROVIDED',
                'full_value' => $clientSecretKey, // Remove in production
            ],
            'server_secret' => [
                'configured' => !empty($serverSecretKey),
                'length'     => strlen($serverSecretKey ?? ''),
                'preview'    => $serverSecretKey ? substr($serverSecretKey, 0, 4) . '...' : 'NOT SET',
                'full_value' => $serverSecretKey, // Remove in production
            ],
            'comparison' => [
                'match'           => $clientSecretKey === $serverSecretKey,
                'both_not_empty'  => !empty($clientSecretKey) && !empty($serverSecretKey),
            ],
            'debug_info' => [
                'client_type'    => gettype($clientSecretKey),
                'server_type'    => gettype($serverSecretKey),
                'client_trimmed' => trim($clientSecretKey ?? ''),
                'server_trimmed' => trim($serverSecretKey ?? ''),
                'trimmed_match'  => trim($clientSecretKey ?? '') === trim($serverSecretKey ?? ''),
            ],
        ];

        Log::info('Manual auth test results', $result);

        return response()->json($result);
    }
}