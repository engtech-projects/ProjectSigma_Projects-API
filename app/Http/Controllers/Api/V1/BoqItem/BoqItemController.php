<?php

namespace App\Http\Controllers\Api\V1\BoqItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoqItem\StoreBoqItemRequest;
use App\Http\Requests\BoqItem\UpdateBoqItemRequest;
use App\Http\Requests\UpdateDraftUnitPriceRequest;
use App\Http\Resources\Project\BoqItemResource;
use App\Http\Resources\SummarizedBoqItemResource;
use App\Models\BoqItem;
use App\Services\BoqItemService;

class BoqItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $boqItems = BoqItem::with('resources')->get();
        return SummarizedBoqItemResource::collection($boqItems)
            ->additional([
                'success' => true,
                'message' => 'All BOQ items retrieved successfully.',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoqItemRequest $request)
    {
        $validated = $request->validated();
        return response()->json([
            'message' => 'Project tasks added successfully.',
            'data' => BoqItemService::create($validated),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqItem $task)
    {
        $task->load(['project', 'resources']);
        return BoqItemResource::make($task)
            ->additional([
                'success' => true,
                'message' => 'BOQ Item retrieved successfully.',
            ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoqItemRequest $request, BoqItem $task)
    {
        $task->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Project item has been updated',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqItem $task)
    {
        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project Task has been deleted',
        ], 200);
    }

    public function restore($task)
    {
        $deletedTask = BoqItem::withTrashed()->findOrFail($task);
        $deletedTask->restore();
        return response()->json([
            'success' => true,
            'message' => 'BOQ Item restored successfully',
        ], 200);
    }

    public function updateDraftUnitPrice(BoqItem $task, UpdateDraftUnitPriceRequest $request)
    {
        $task->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Draft unit price updated successfully.',
        ]);
    }
}
