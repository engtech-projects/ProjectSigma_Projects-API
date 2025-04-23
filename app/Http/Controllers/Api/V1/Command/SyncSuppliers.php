<?php

namespace App\Http\Controllers\Api\V1\Command;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncSuppliers extends Controller
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
                ->get($apiUrl.'/api/sigma/suppliers');

            if ($response->ok()) {
                return $response->json()['data'];
                $itemProfiles = $response->json()['data'] ?? [];

                // DB::transaction(function () use ($itemProfiles) {

                //     foreach ($itemProfiles as $item) {
                //         ItemProfile::updateOrCreate(
                //             ['id' => $item['id']], // Prevent duplicate employees
                //             [
                //                 'created_at' => $item['created_at'] ? Carbon::parse($employee['created_at'])->format('Y-m-d H:i:s') : null,
                //                 'updated_at' => $item['updated_at'] ? Carbon::parse($employee['updated_at'])->format('Y-m-d H:i:s') : null,
                //             ],
                //         );
                //     }
                // });
            }

        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
