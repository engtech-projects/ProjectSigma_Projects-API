<?php

namespace App\Http\Controllers\Api\V1\Phase;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterPhraseRequest;
use App\Http\Requests\Phase\StorePhaseRequest;
use App\Http\Requests\Phase\UpdatePhaseRequest;
use App\Models\Phase;
use App\Models\Project;
use App\Services\BOQPartService;
use App\Services\ProjectService;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterPhraseRequest $request)
    {
        $phases = BOQPartService::withPagination($request->validated());

        return response()->json([
            'message' => 'Phases retrieved successfully.',
            'data' => $phases,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhaseRequest $request, ProjectService $projectService)
    {
        $validated = $request->validated();
        $project = Project::find($validated['project_id']);
        $result = BOQPartService::create($validated);

        return response()->json([
            'message' => 'Project Item added successfully.',
            'data' => $result,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Phase $phase)
    {
        return response()->json($phase->load('tasks'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseRequest $request, Phase $phase)
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
    public function destroy(Phase $phase)
    {
        $phase->delete();

        return response()->json([
            'message' => 'Project phase has been deleted',
            'data' => $phase,
        ], 200);
    }
}
