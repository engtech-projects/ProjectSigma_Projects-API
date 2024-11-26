<?php

namespace App\Http\Controllers\Api\V1\Phase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phase;
use App\Models\Project;
use App\Http\Resources\Phase\PhaseCollection;
use App\Http\Resources\Phase\PhaseResource;
use App\Http\Requests\Phase\StorePhaseRequest;
use App\Http\Requests\Phase\UpdatePhaseRequest;
use App\Services\ProjectService;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		// $projects = Project::original()->latest()->paginated(10);
		// return response()->json(new ProjectCollection($projects), 200);
    }

	
	/**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhaseRequest $request, ProjectService $projectService)
    {	
		$validated = $request->validated();

		$project = Project::find($validated['project_id']);

		$result = $projectService->addPhases($project, $validated['phases']);

		if (isset($result['error'])) {
			return response()->json([
				'message' => 'Failed to add Project phases.',
				'error' => $result['error']
			], 500);
		}

		return response()->json([
			'message' => 'Project phases added successfully.',
			'data' => $result
		], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Phase $phase)
    {
		return response()->json($phase->tasks(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseRequest $request, Phase $phase)
    {

        $validated = $request->validated();
	
		try {
			$phase->update($validated);
	
			return response()->json([
				'message' => 'Project phase has been updated',
				'data' => $phase,
			], 200);

		} catch (\Throwable $e) {

			return response()->json([
				'message' => 'Failed to update the project phase.',
				'error' => $e->getMessage()
			], 500);
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phase $phase)
    {
        
    }
}
