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
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;


class ProjectController extends Controller
{
    protected $projectService;
    protected $request;
    public function __construct(ProjectService $projectService, Request $request)
    {
        $this->projectService = $projectService;
        $this->request = $request;
    }

    public function index()
    {

        $projects = $this->projectService->getProjects($this->request->get('completion_status'));
        throw_if(empty($projects->toArray()), new NoRecordFoundException());

        return ProjectResource::collection($projects);
    }


    public function store(): JsonResponse
    {
        //$attributes = $request->validated();
        $project = $this->projectService->createProject($this->request->input());

        return new JsonResponse([
            'data' => new ProjectResource($project),
            'message' => "Project created."
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }



    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $attributes = $request->validated();
        $project = $this->projectService->updateProject($attributes, $project);

        return new ProjectResource($project);
    }


    public function destroy(Project $project): JsonResponse
    {
        $project = $this->projectService->deleteProject($project);

        return new JsonResponse(['message' => 'Project deleted.']);
    }
}
