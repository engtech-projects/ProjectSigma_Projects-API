<?php

namespace App\Http\Controllers;

use App\Exceptions\NoRecordFoundException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $projects = $this->projectService->getProjects($request->get('status'));
        throw_if(empty($projects->toArray()),new NoRecordFoundException());
        return response()->json(new ProjectResource($projects));
    }


    public function store(StoreProjectRequest $request)
    {
        $attributes = $request->validated();
        $project = $this->projectService->createProject($attributes);


        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Created."
        ]);
    }

    public function show(Project $project)
    {

        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Fetched."
        ]);
    }



    public function update(UpdateProjectRequest $request, Project $project)
    {
        $attributes = $request->validated();
        $project = $this->projectService->updateProject($attributes,$project);
        return $this->sendSuccessResponse([
            'data' => new ProjectResource($project),
            "message" => "Project Updated."
        ]);
    }


    public function destroy(Project $project)
    {
        $project = $this->projectService->deleteProject($project);
        return $this->sendSuccessResponse(["message" => $project]);
    }
}
