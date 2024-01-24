<?php

namespace App\Http\Controllers;

use App\Exceptions\NoRecordFoundException;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    protected $projectService;
    protected $request;
    public function __construct(ProjectService $projectService,Request $request) {
        $this->projectService = $projectService;
        $this->request = $request;
    }

    public function index()
    {
        $projects = $this->projectService->getProjects($this->request->get('completion_status'));
        throw_if(empty($projects->toArray()),new NoRecordFoundException());
        return new JsonResponse($projects);
    }


    public function store(StoreProjectRequest $request): ProjectResource
    {
        $attributes = $request->validated();
        $project = $this->projectService->createProject($attributes);
        return new ProjectResource($project);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }



    public function update(UpdateProjectRequest $request, Project $project) : ProjectResource
    {
        $attributes = $request->validated();
        $project = $this->projectService->updateProject($attributes,$project);
        return new ProjectResource($project);
    }


    public function destroy(Project $project) : JsonResponse
    {
        $project = $this->projectService->deleteProject($project);
        return new JsonResponse(['message' => 'Project deleted.'],200);
    }
}
