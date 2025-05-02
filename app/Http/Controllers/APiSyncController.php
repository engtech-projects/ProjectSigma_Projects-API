<?php

namespace App\Http\Controllers;

use App\Services\ApiServices\InventoryService;
use DB;
use Illuminate\Http\Request;

class APiSyncController extends Controller
{
    public function syncUOM(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $inventoryService = new InventoryService($authToken);
            if (! $inventoryService->syncUOM()) {
                throw new \Exception('UOM sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all employees.',
            'success' => true,
        ]);
    }
}
