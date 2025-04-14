<?php

namespace App\Http\Controllers\Api\V1\Position;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\EditPositionRequest;
use App\Http\Requests\Position\FilterPositionRequest;
use App\Http\Requests\Position\StorePositionRequest;
use App\Models\position;
use App\Services\PositionService;
use Illuminate\Http\Request;

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
    public function show(position $position)
    {
        $position = PositionService::show($position);
        return response()->json([
            'message' => 'Position retrieved successfully.',
            'data' => $position,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditPositionRequest $request, position $position)
    {
        $validatedData = $request->validated();
        $position = PositionService::update($validatedData, $position);
        return response()->json([
            'message' => 'Position updated successfully.',
            'data' => $position,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(position $position)
    {
        $position = PositionService::delete($position);
        return response()->json([
            'message' => 'Position deleted successfully.',
            'data' => $position,
        ], 200);
    }
}
