<?php

namespace App\Services\ApiServices;

use App\Models\SetupItemProfiles;
use App\Models\Uom;
use DB;
use Http;

class InventoryService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.url.inventory_api');
        $this->authToken = config('services.sigma.secret_key');
        if (empty($this->authToken)) {
            throw new \InvalidArgumentException('SECRET KEY is not configured');
        }
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('Projects API URL is not configured');
        }
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
        Uom::upsert(
            $uoms,
            ['id'],
            [
                'name',
                'symbol',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        );
        return true;
    }

    public function getUOMs()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/uoms');
        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }

    public function syncItemProfile()
    {
        $data = $this->getItemProfileList();
        SetupItemProfiles::upsert(
            $data,
            ['id'],
            [
                'item_code',
                'item_description',
                'thickness',
                'length',
                'width',
                'height',
                'outside_diameter',
                'inside_diameter',
                'angle',
                'size',
                'specification',
                'volume',
                'weight',
                'grade',
                'volts',
                'plates',
                'part_number',
                'color',
                'uom',
                'uom_conversion_value',
                'item_group',
                'sub_item_group',
                'inventory_type',
                'active_status',
                'is_approved',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        );
        return true;
    }

    public function getItemProfileList()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/item-profiles');
        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }
}
