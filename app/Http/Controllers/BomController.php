<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBomRequest;
use App\Http\Requests\UpdateBomRequest;
use App\Http\Resources\BomResource;
use App\Models\Project;

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
        $bom = $project->boms()->create($request->validated());
        return BomResource::make($bom)
            ->additional([
                'success' => true,
                'message' => 'Bill of Material created successfully',
            ]);
    }
    public function show(Project $project, $bomId)
    {
        $bom = $project->boms()->findOrFail($bomId);
        return BomResource::make($bom)
            ->additional([
                'success' => true,
                'message' => 'Bill of Material item retrieved successfully',
            ]);
    }
    public function update(Project $project, $bomId, UpdateBomRequest $request)
    {
        $bom = $project->boms()->findOrFail($bomId);
        $bom->update($request->validated());
        return response()->json([
                'success' => true,
                'message' => 'Bill of Material item updated successfully',
            ]);
    }
    public function destroy(Project $project, $bomId)
    {
        $bom = $project->boms()->findOrFail($bomId);
        $bom->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bill of Material item deleted successfully',
        ], 200);
    }
}
