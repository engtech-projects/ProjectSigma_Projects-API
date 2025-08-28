<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProjectRequest;
use App\Http\Requests\Project\ReplicateProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\SummaryRate\SummaryRateRequest;
use App\Http\Requests\UpdateCashFlowRequest;
use App\Http\Requests\UpdateProjectStageRequest;
use App\Http\Resources\Project\ProjectDetailResource;
use App\Http\Resources\Project\ProjectListingResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

// use Illuminate\Support\Facades\Gate;

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
    public function index(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $projectKey = $validated['project_key'] ?? null;
        $status = $validated['stage_status'] ?? null;
        $data = Project::with('revisions')
            ->when($status, fn ($query) => $query->filterByStage($status))
            ->when($projectKey, fn ($query) => $query->projectKey($projectKey))
            ->latestFirst()
            ->paginate(config('services.pagination.limit'));
        return ProjectListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }

    public function getOwnedProjects(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $projectKey = $validated['project_key'] ?? null;
        $status = $validated['stage_status'] ?? null;
        $data = Project::with('revisions')
            ->when($status, fn ($query) => $query->filterByStage($status))
            ->when($projectKey, fn ($query) => $query->projectKey($projectKey))
            ->latestFirst()
            ->createdByAuth()
            ->paginate(config('services.pagination.limit'));
        return ProjectListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }

    public function replicate(ReplicateProjectRequest $request)
    {
        $validatedData = $request->validated();
        $result = ProjectService::replicate($validatedData);
        return response()->json([
            'message' => 'Project replicated successfully.',
            'data' => $result,
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $result = $this->projectService->create($validated);
        return response()->json([
            'message' => 'Project created successfully.',
            'data' => $result,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $resource)
    {
        $data = $resource->load('phases.tasks', 'attachments');
        return new JsonResponse([
            'success' => true,
            'message' => "Successfully fetched.",
            'data' => new ProjectDetailResource($data),
        ], JsonResponse::HTTP_OK);
    }

    public function getLiveProjects()
    {
        $data = Project::ongoing()
            ->latestFirst()
            ->createdByAuth()
            ->paginate(config('services.pagination.limit'));
        return ProjectListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $resource)
    {
        $validated = $request->validated();
        $result = $this->projectService->update($resource, $validated);
        return response()->json([
            'message' => 'Project has been updated.',
            'data' => $result,
        ], 200);
    }

    public function changeSummaryRates(SummaryRateRequest $request)
    {
        $validated = $request->validated();
        $summaryOfRates = $this->projectService->changeSummaryRates($validated);
        return $summaryOfRates;
    }

    public function updateStage(UpdateProjectStageRequest $request, Project $project)
    {
        $valid = $request->validated();
        $oldStage = $project->marketing_stage->value;
        $newStage = $valid['stage'];
        $newStageEnum = ProjectStage::validateTransition($oldStage, $newStage);
        $projectService = new ProjectService($project);
        try {
            $projectService->updateStage($newStageEnum);
            return new JsonResponse([
                'success' => true,
                'message' => "Successfully updated stage from {$oldStage} to {$newStage}.",
            ], JsonResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to update stage from {$oldStage} to {$newStage}.",
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function tssProjects(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $projectKey = $validated['project_key'] ?? null;
        $status = $validated['stage_status'] ?? null;
        $projects = Project::query()
            ->when($status, fn ($query) => $query->awarded())
            ->when($projectKey, fn ($query) => $query->projectKey($projectKey))
            ->latestFirst()
            ->paginate(config('services.pagination.limit'));
        return ProjectListingResource::collection($projects)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.'
            ]);
    }

    public function updateCashFlow(UpdateCashFlowRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->cash_flow = $validated['cash_flow'];
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Cash flow updated successfully.',
            'data' => $project,
        ], 200);
    }
}
