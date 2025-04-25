<?php

namespace App\Http\Controllers\Api\V1\ResourceItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceItem\StoreResourceItemRequest;
use App\Http\Requests\ResourceItem\UpdateResourceItemRequest;
use App\Models\ResourceItem;
use App\Services\ResourceService;

class ResourceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'message' => 'task resource allocation added successfully.',
            'data' => $result,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => $id,
        ], 201);
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

        $resourceItem->fill($validated)->save();

        return response()->json([
            'message' => 'Updated.',
            'data' => $resourceItem,
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
