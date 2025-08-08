<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResourceMetricRequest;
use App\Http\Requests\UpdateResourceMetricRequest;
use App\Models\ResourceItem;
use App\Models\ResourceMetric;
use Illuminate\Http\Request;

class ResourceMetricController extends Controller
{
    public function index()
    {
        return ResourceMetric::with('resource')->get();
    }

    public function store(CreateResourceMetricRequest $request)
    {
        $validatedData = $request->validated();
        $resourceMetric = ResourceMetric::create([
            'resource_id' => $validatedData['resource_id'],
            'label' => $validatedData['label'],
            'value' => $validatedData['value'],
            'unit' => $validatedData['unit'],
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Resource metric created successfully',
            'data' => $resourceMetric,
        ], 201);
    }

    public function update(UpdateResourceMetricRequest $request, ResourceMetric $resourceMetric)
    {
        $validatedData = $request->validated();
        $resourceMetric->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Resource metric updated successfully',
            'data' => $resourceMetric,
        ], 200);
    }

    public function destroy(ResourceMetric $resourceMetric)
    {
        $resourceMetric->delete();
        return response()->json([
            'success' => true,
            'message' => 'Resource metric deleted successfully',
            'data' => $resourceMetric,
        ], 200);
    }
}
