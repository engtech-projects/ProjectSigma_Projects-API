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

    public function index(Request $request)
    {
        return ProjectResource::collection(Project::all());

/*         $projects = $this->projectService->getProjects($request->get('completion_status'));
        throw_if(empty($projects->toArray()), new NoRecordFoundException());

        return ProjectResource::collection($projects); */
    }


    public function store(StoreProjectRequest $request): JsonResponse
    {
        /*  $attributes = $request->validated(); */
        $this->projectService->createProject($request->validated());

        return new JsonResponse([
            'message' => "Project created."
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }



    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $attributes = $request->validated();
        $this->projectService->updateProject($attributes, $project);

        return new JsonResponse([
            'message' => "Project updated."
        ]);
    }


    public function destroy(Project $project): JsonResponse
    {
        $this->projectService->deleteProject($project);

        return new JsonResponse(['message' => 'Project deleted.']);
    }
}
