<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterDirectCostEstimateRequest;
use App\Http\Resources\DirectCostEstimateResource;
use App\Models\ResourceItem;

class DirectCostEstimateController extends Controller
{
    public function index(FilterDirectCostEstimateRequest $request)
    {
        $validated = $request->validated();
        $task_id = $validated['task_id'] ?? null;
        $resource_type = $validated['resource_type'] ?? null;
        $data = ResourceItem::with('project', 'task')
            ->when($task_id, fn ($query) => $query->filterByTaskId($task_id))
            ->when($resource_type, fn ($query) => $query->filterByResourceType($resource_type))
            ->get();
        return DirectCostEstimateResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Direct Cost Estimates retrieved successfully',
            ]);
    }

    public function show($id)
    {
        $resourceItem = ResourceItem::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Direct Cost Estimate retrieved successfully',
            'data' => new DirectCostEstimateResource($resourceItem),
        ], 200);
    }
}
