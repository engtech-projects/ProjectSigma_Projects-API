<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SyncUnits extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $apiUrl = config('services.url.inventory_api_url');
        $apiKey = config('services.sigma.secret_key');
        $token = $request->bearerToken();

        try {
            // switch token and apiKey
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->get($apiUrl.'/api/sigma/uoms');

            return $response;
            if ($response->ok()) {
                $units = $response->json()['data'] ?? [];
            }

        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
