<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use Illuminate\Http\Response;
use App\Services\ProjectService;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResource;
use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;

class ProjectController extends Controller
{

	protected $projectService;

	public function __construct(ProjectService $projectService)
    {
		$this->projectService = $projectService;
    }

	/**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		// $projects = Project::original()->latest()->paginate(10);
		// return response()->json(new ProjectCollection($projects), 200);

        $stageMethods = [
            ProjectStage::AWARDED->label() => 'internal',
        ];
        
        $statusMethods = [
            ProjectStatus::ONGOING->label() => 'active',
            ProjectStatus::ARCHIVED->label() => 'archived',
        ];

        $query = Project::query();

        if (isset($stageMethods[$request->stage])) {
            $method = $stageMethods[$request->stage];
            $query->$method();
        }

        if (isset($statusMethods[$request->status])) {
            $method = $statusMethods[$request->status];
            $query->$method();
        }

        $projects = $query->latest()->get();

		return response()->json(new ProjectCollection($projects), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {	
		$validated = $request->validated();

		$result = $this->projectService->create($validated);

		if (isset($result['error'])) {
			return response()->json([
				'message' => 'Failed to create the project.',
				'error' => $result['error']
			], 500);
		}

		return response()->json([
			'message' => 'Project created successfully.',
			'data' => $result
		], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {

		return response()->json(new ProjectResource($project), 200);
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
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

		$result = $this->projectService->update($project, $validated);

		if (isset($result['error'])) {
			return response()->json([
				'message' => 'Failed to update the project.',
				'error' => $result['error']
			], 500);
		}

		return response()->json([
			'message' => 'Project has been updated.',
			'data' => $result
		], 200);
    }

    /**
     * 
     */
    public function archive(Project $project)
    {
        
    }

}
