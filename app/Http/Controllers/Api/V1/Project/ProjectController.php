<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Enums\TssStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProjectRequest;
use App\Http\Requests\Project\ReplicateProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\SummaryRate\SummaryRateRequest;
use App\Http\Requests\UpdateCashFlowRequest;
use App\Http\Requests\UpdateProjectChecklistRequest;
use App\Http\Requests\UpdateProjectStageRequest;
use App\Http\Resources\DraftItemListResource;
use App\Http\Resources\Project\ProjectDetailResource;
use App\Http\Resources\Project\ProjectListingResource;
use App\Http\Resources\Project\ProjectLiveDetailResource;
use App\Http\Resources\Project\ProjectLiveListingResource;
use App\Http\Resources\ProjectCompletionReportResource;
use App\Http\Resources\ProjectDataSheetResource;
use App\Http\Resources\SummaryOfDirectEstimateResource;
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
    public function getResourcesItems(Project $project)
    {
        if (!$project) {
            throw ValidationException::withMessages([
                'project_id' => 'The project does not exist.',
            ]);
        }
        $data = $project->resources();
        if (!$data) {
            throw new \Exception('No resources found for the project.');
        }
        return DraftItemListResource::collection($data)
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
    public function getProjectDetails(Project $project)
    {
        // Base relations
        $relations = ['phases.tasks', 'attachments'];
        if ($project->tss_status !== TssStatus::PENDING->value) {
            $relations[] = 'directCostApprovalRequest';
        }
        $project->load($relations);
        return new JsonResponse([
            'success' => true,
            'message' => "Successfully fetched.",
            'data' => new ProjectLiveDetailResource($project),
        ], JsonResponse::HTTP_OK);
    }
    public function getLiveProjects(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $projectKey = $validated['project_key'] ?? null;
        $data = Project::ongoing()
            ->when($projectKey, fn ($query) => $query->projectKey($projectKey))
            ->latestFirst()
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
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
    public function generateSummaryOfDirectEstimate(Project $project)
    {
        $projectService = new ProjectService($project);
        $summary = $projectService->getTasksWithResources();
        $distributionOfDirectCost = $projectService->calculateDirectCostDistribution($summary);
        return SummaryOfDirectEstimateResource::collection($summary)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched summary of direct estimate.',
                'project_code' => $project->code,
                'project_name' => $project->name,
                'location' => $project->location,
                'document_number' => $project->document_number,
                'revision_no' => $project->revision_no ?? 0,
                'distribution_of_direct_cost' => $distributionOfDirectCost,
            ]);
    }
    public function getProjectChecklist(Project $project)
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully fetched project checklist.',
            'data' => $project->project_checklist,
        ], 200);
    }
    public function updateProjectChecklist(UpdateProjectChecklistRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->project_checklist = $validated['project_checklist'];
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Project checklist updated successfully.',
        ], 200);
    }
    public function getDataSheet(Project $project)
    {
        return ProjectDataSheetResource::make($project)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched data sheet.',
            ]);
    }
    public function getCompletionReport(Project $project)
    {
        return ProjectCompletionReportResource::make($project)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched completion report.',
            ]);
    }
}
