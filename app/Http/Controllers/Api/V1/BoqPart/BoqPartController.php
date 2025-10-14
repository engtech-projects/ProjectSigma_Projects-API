<?php

namespace App\Http\Controllers\Api\V1\BoqPart;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterPhraseRequest;
use App\Http\Requests\BoqPart\StoreBoqPartRequest;
use App\Http\Requests\BoqPart\UpdateBoqPartRequest;
use App\Http\Resources\Project\BoqPartResource;
use App\Models\BoqPart;
use App\Models\Project;
use App\Services\BoqPartService;
use App\Services\ProjectService;

class BoqPartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterPhraseRequest $request)
    {
        $phases = BoqPartService::withPagination($request->validated());

        return response()->json([
            'message' => 'Phases retrieved successfully.',
            'data' => BoqPartResource::collection($phases),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoqPartRequest $request, ProjectService $projectService)
    {
        $validated = $request->validated();
        $project = Project::find($validated['project_id']);
        $result = BoqPartService::create($validated);

        return response()->json([
            'message' => 'Project Item added successfully.',
            'data' => $result,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqPart $phase)
    {
        return response()->json($phase->load('tasks'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoqPartRequest $request, BoqPart $phase)
    {
        $validated = $request->validated();
        $phase->update($validated);

        return response()->json([
            'message' => 'Project phase has been updated',
            'data' => $phase,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqPart $phase)
    {
        $phase->delete();
        return response()->json([
            'message' => 'Project phase has been deleted',
            'data' => $phase,
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($phase)
    {
        $deletedPhase = BoqPart::withTrashed()->findOrFail($phase);
        $deletedPhase->restore();
        return response()->json([
            'success' => true,
            'message' => 'Project phase has been restored',
        ], 200);
    }
}
