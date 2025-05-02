<?php

namespace App\Services\ApiServices;

use App\Models\Uom;
use DB;
use Http;

class InventoryService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
        $this->apiUrl = config('services.url.inventory_api');
    }

    public function syncAll()
    {
        $syncData = [
            'uom' => $this->syncUOM(),
        ];

        return $syncData;
    }

    public function syncUOM()
    {
        $uoms = $this->getUOMs();
        $uoms_data = collect($uoms)->map(function ($uom) {
            return [
                'stakeholdable_id' => $uom['id'],
                'stakeholdable_type' => Uom::class,
                'name' => $uom['name'],
            ];
        });
        DB::transaction(function () use ($uoms, $uoms_data) {
            Uom::upsert(
                $uoms->toArray(),
                ['source_id'],
                ['name']
            );
            Uom::upsert(
                $uoms_data->toArray(),
                [
                    'stakeholdable_type',
                    'stakeholdable_id',
                ],
                ['name']
            );
        });

        return true;
    }

    public function getUOMs()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl.'/api/uoms');
        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }
}
