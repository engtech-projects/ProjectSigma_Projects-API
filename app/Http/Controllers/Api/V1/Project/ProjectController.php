<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\FilterProjectRequest;
use App\Http\Requests\Project\ReplicateProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\SummaryRate\SummaryRateRequest;
use App\Http\Requests\UpdateProjectStageRequest;
use App\Http\Resources\Project\ProjectDetailResource;
use App\Http\Resources\Project\ProjectListingResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

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
        $validate = $request->validated();

        $data = Project::with('revisions')->when(!empty($validate['stage']), function ($query) use ($validate) {
            $query->filterByStage($validate['stage']);
        })->paginate(config('services.pagination.limit'));

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
    public function show(Project $project)
    {
        $data = $project->load('phases.tasks');
        return new JsonResponse([
            'success' => true,
            'message' => "Successfully fetched.",
            'data' => new ProjectDetailResource($data),
        ], JsonResponse::HTTP_OK);
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
                'error' => $result['error'],
            ], 500);
        }

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

    public function updateStage(UpdateProjectStageRequest $request, $id)
    {
        $valid = $request->validate();
        $project = Project::findOrFail($id);
        $newStage = ProjectStage::from($valid['stage']);

        try {
            $oldStage = $project->updateStage($newStage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to update stage from {$oldStage} to {$newStage->value}.",
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse([
            'success' => true,
            'message' => "Successfully updated stage from {$oldStage} to {$newStage->value}.",
        ], JsonResponse::HTTP_OK);
    }

    public function filterProjects(FilterProjectRequest $request)
    {
        $validated = $request->validated();

        $projectKey = $validated['project_key'];
        $status = $validated['stage_status'] ?? null;

        $projects = Project::query()
            ->where(function ($query) use ($projectKey) {
                $query->where('name', 'like', '%' . $projectKey . '%')
                    ->orWhere('code', 'like', '%' . $projectKey . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('marketing_stage', $status)
                    ->orWhere('tss_stage', $status);
            })
            ->paginate(config('services.pagination.limit'));

        return response()->json([
            'success' => true,
            'message' => 'Projects found.',
            'data' => $projects,
        ], 200);
    }
}
