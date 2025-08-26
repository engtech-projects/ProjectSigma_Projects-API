<?php

namespace App\Services\ApiServices;

use App\Models\SetupItemProfiles;
use App\Models\Uom;
use Illuminate\Support\Facades\Http;

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
        $syncUOMs = $this->syncUOM();
        $syncItemProfiles = $this->syncItemProfile();
        return $syncUOMs && $syncItemProfiles;
    }

    public function syncUOM()
    {
        $uoms = $this->getUOMs();
        $uoms = array_map(fn ($uom) => [
            "id" => $uom['id'],
            "name" => $uom['name'],
            "symbol" => $uom['symbol'],
            "created_at" => $uom['created_at'],
            "updated_at" => $uom['updated_at'],
            "deleted_at" => $uom['deleted_at'],
        ], $uoms["data"]);
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
        if (!$response->successful()) {
            return false;
        }
        return $response->json();
    }

    public function syncItemProfile()
    {
        $datas = collect($this->getItemProfileList()['data'])
            ->map(fn ($data) => [
                'id'              => $data['id'],
                'item_code' => $data['item_name_summary'],
                'item_description' => $data['item_name_summary'],
                'uom'             => $data['uom'],
                'item_group'      => $data['uom_name'],
                'active_status'   => $data['status'],
                'created_at'      => $data['created_at'],
                'updated_at'      => $data['updated_at'],
                'deleted_at'      => $data['deleted_at'],
            ])
            ->values()
            ->all();
        SetupItemProfiles::upsert(
            $datas,
            ['id'],
            [
                'item_code',
                'item_description',
                'uom',
                'item_group',
                'active_status',
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
