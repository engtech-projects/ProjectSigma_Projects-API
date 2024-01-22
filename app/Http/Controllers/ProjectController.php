<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $projects = $this->projectService->getProjects();
        return response()->json(new ProjectResource($projects));
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
    public function store(StoreProjectRequest $request)
    {
        $attributes = $request->validated();
        $project = $this->projectService->createProject($attributes);

        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Created."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Fetched."
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $attributes = $request->validated();
        $project = $this->projectService->updateProject($attributes,$project);
        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project = $this->projectService->deleteProject($project);
        return $this->sendSuccessResponse(["message" => $project]);
    }
}
