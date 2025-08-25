<?php

namespace App\Http\Controllers;

use App\Jobs\ApiInventorySyncJob;
use App\Services\ApiServices\InventoryService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APiSyncController extends Controller
{
    public function syncUOM(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncUOM');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch UOM sync job', ['error' => $e->getMessage()]);
            throw new \Exception("UOM sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all UOMs.',
            'success' => true,
        ]);
    }

    public function syncItemProfile(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncItemProfile');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch UOM sync job', ['error' => $e->getMessage()]);
            throw new \Exception("UOM sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all UOMs.',
            'success' => true,
        ]);
    }
}
