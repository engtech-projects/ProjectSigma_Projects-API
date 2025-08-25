<?php

namespace App\Http\Controllers;

use App\Jobs\ApiInventorySyncJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APiSyncController extends Controller
{
    public function syncAllHrms(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncAllHrms');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch HRMS sync job', ['error' => $e->getMessage()]);
            throw new \Exception("HRMS sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all HRMS.',
            'success' => true,
        ]);
    }
    public function syncEmployees(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncEmployees');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Employee sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Employee sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all Employees.',
            'success' => true,
        ]);
    }
    public function syncAccessibilities(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncAccessibilities');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Accessibility sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Accessibility sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all Accessibilitys.',
            'success' => true,
        ]);
    }
    public function syncDepartments(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncDepartments');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Department sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Department sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all Departments.',
            'success' => true,
        ]);
    }

    public function syncAllInventory(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncAll');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Inventory sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Inventory sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all Inventorys.',
            'success' => true,
        ]);
    }

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
            Log::error('Failed to dispatch ItemProfile sync job', ['error' => $e->getMessage()]);
            throw new \Exception("ItemProfile sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all ItemProfiles.',
            'success' => true,
        ]);
    }
}
