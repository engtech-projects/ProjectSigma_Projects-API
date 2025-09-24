<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBomRequest;
use App\Http\Resources\BomResource;
use App\Models\Project;
use Illuminate\Http\Request;

class BomController extends Controller
{
    public function index(Project $project)
    {
        return BomResource::collection($project->boms()->get())
            ->additional([
                'success' => true,
                'message' => 'Bill of Materials retrieved successfully',
            ]);
    }
    public function store(Project $project, StoreBomRequest $request)
    {
        $bomItem = $project->boms()->create($request->validated());
        return (new BomResource($bomItem))
            ->additional([
                'success' => true,
                'message' => 'Bill of Material item created successfully',
            ]);
    }
    public function show(Project $project, $bomId)
    {
        $bomItem = $project->boms()->findOrFail($bomId);
        return (new BomResource($bomItem))
            ->additional([
                'success' => true,
                'message' => 'Bill of Material item retrieved successfully',
            ]);
    }
    public function update(Project $project, $bomId, StoreBomRequest $request)
    {
        $bomItem = $project->boms()->findOrFail($bomId);
        $bomItem->update($request->validated());
        return response()->json([
                'success' => true,
                'message' => 'Bill of Material item updated successfully',
            ]);
    }
    public function destroy(Project $project, $bomId)
    {
        $bomItem = $project->boms()->findOrFail($bomId);
        $bomItem->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bill of Material item deleted successfully',
        ], 200);
    }
}
