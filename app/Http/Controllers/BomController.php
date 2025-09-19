<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBomRequest;
use App\Http\Requests\UpdateBomRequest;
use App\Http\Resources\BomResource;
use App\Models\Project;
use App\Models\Bom;

class BomController extends Controller
{
    public function index(Project $project)
    {
        return BomResource::collection($project->bom()->get());
    }
    public function store(Project $project, StoreBomRequest $request)
    {
        $bom = $project->bom()->create($request->validated());
        return BomResource::make($bom)
            ->additional([
                'success' => true,
                'message' => 'Bill of Materials item created successfully',
            ]);
    }
    public function update(Project $project, Bom $bom, UpdateBomRequest $request)
    {
        if ($bom->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'The specified Bill of Materials item does not belong to the given project.',
            ], 404);
        }
        $bom->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Bill of Materials item updated successfully',
        ]);
    }
    public function destroy(Project $project, Bom $bom)
    {
        if ($bom->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'The specified Bill of Materials item does not belong to the given project.',
            ], 404);
        }
        $bom->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bill of Materials item deleted successfully',
        ]);
    }
}
