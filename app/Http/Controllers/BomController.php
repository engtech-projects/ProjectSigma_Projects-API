<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBomRequest;
use App\Http\Requests\UpdateBomRequest;
use App\Http\Resources\BomResource;
use App\Http\Resources\GenerateBomResource;
use App\Models\Bom;
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
    public function generateBillOfMaterials(Project $project)
    {
        return GenerateBomResource::collection($project->boms()->get())
            ->additional([
                'success' => true,
                'message' => 'Bill of Materials generated successfully',
                'project_code' => $project->code,
                'project_name' => $project->name,
                'location' => $project->location,
                'scope_of_work' => $project->nature_of_work,
            ]);
    }
}
