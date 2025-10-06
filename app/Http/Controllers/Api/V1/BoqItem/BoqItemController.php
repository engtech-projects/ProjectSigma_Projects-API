<?php

namespace App\Http\Controllers\Api\V1\BoqItem;

use App\Enums\AccessibilityProjects;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoqItem\StoreBoqItemRequest;
use App\Http\Requests\BoqItem\UpdateBoqItemRequest;
use App\Http\Requests\UpdateDraftUnitPriceRequest;
use App\Http\Resources\Project\BoqItemResource;
use App\Http\Traits\CheckAccessibility;
use App\Models\BoqPart;
use App\Models\BoqItem;
use App\Services\BoqItemService;
use Illuminate\Http\Request;

class BoqItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CheckAccessibility;

    public function index(Request $request)
    {
        if ($request->has('phase_id')) {
            $phase = BoqPart::find($request->phase_id);
            return response()->json($phase->load('tasks.resources'), 200);
        }
        return response()->json(BoqItem::all()->load('resources'), 200);
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

    public function updateDraftUnitPrice(BoqItem $task, UpdateDraftUnitPriceRequest $request)
    {
        if (!$this->checkUserAccess([
            ...AccessibilityProjects::marketingGroup(),
        ])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $task->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Draft unit price updated successfully.',
        ]);
    }
}
