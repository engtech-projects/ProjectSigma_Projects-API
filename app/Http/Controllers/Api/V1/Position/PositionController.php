<?php

namespace App\Http\Controllers\Api\V1\Position;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\EditPositionRequest;
use App\Http\Requests\Position\FilterPositionRequest;
use App\Http\Requests\Position\StorePositionRequest;
use App\Models\Position;
use App\Services\PositionService;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterPositionRequest $request)
    {
        $validatedData = $request->validated();
        $positions = PositionService::withPagination($validatedData);

        return response()->json([
            'message' => 'Positions retrieved successfully.',
            'data' => $positions,
        ], 200);
    }

    public function all()
    {
        $positions = PositionService::all();

        return response()->json([
            'message' => 'Positions retrieved successfully.',
            'data' => $positions,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $validatedData = $request->validated();
        $position = PositionService::create($validatedData);

        return response()->json([
            'message' => 'Position created successfully.',
            'data' => $position,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        return response()->json([
            'message' => 'Position retrieved successfully.',
            'data' => $position,
        ], 200);
    }

    /**
     * Update the specified resource in storage (PUT).
     */
    public function update(EditPositionRequest $request, Position $position)
    {
        $validatedData = $request->validated();
        $position->update($validatedData);

        return response()->json([
            'message' => 'Position updated successfully.',
            'data' => $position,
        ], 200);
    }

    /**
     * Partially update the specified resource in storage (PATCH).
     */
    public function patch(EditPositionRequest $request, Position $position)
    {
        $validatedData = $request->validated();
        $position->update($validatedData);

        return response()->json([
            'message' => 'Position updated successfully.',
            'data' => $position,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return response()->json([
            'message' => 'Position deleted successfully.',
            'data' => $position,
        ], 200);
    }
}
