<?php

namespace App\Http\Controllers\Api\V1\ResourceItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceItem\StoreResourceItemRequest;
use App\Http\Requests\ResourceItem\UpdateResourceItemRequest;
use App\Http\Resources\ResourceItemResource;
use App\Models\ResourceItem;
use App\Services\ResourceService;

class ResourceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listofResourceItems = ResourceItem::with('task')->get();
        return ResourceItemResource::collection($listofResourceItems)
            ->additional([
                'success' => true,
                'message' => 'Resource items retrieved successfully',
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResourceItemRequest $request)
    {
        $validated = $request->validated();
        $result = ResourceService::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Resource item added successfully.',
            'data' => $result,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResourceItem $resourceItem)
    {
        $resourceItem->load('task');
        return response()->json([
            'success' => true,
            'message' => 'Resource item retrieved successfully',
            'data' => $resourceItem,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResourceItemRequest $request, ResourceItem $resourceItem)
    {
        $validated = $request->validated();
        $result = ResourceService::update($validated, $resourceItem->id);
        return response()->json([
            'success' => true,
            'message' => 'Resource item updated successfully.',
            'data' => $result,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResourceItem $resourceItem)
    {
        $result = ResourceService::delete($resourceItem->id);
        return response()->json([
            'success' => true,
            'message' => 'Project Resources Item has been deleted',
            'data' => $result,
        ], 200);
    }
}
