<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Enums\TssStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\FilterProjectRequest;
use App\Http\Requests\Project\ReplicateProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\SummaryRate\SummaryRateRequest;
use App\Http\Requests\UpdateProjectStageRequest;
use App\Http\Resources\Project\ProjectDetailResource;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $data = Project::with('revisions')->where('created_by', auth()->user()->id)->paginate(config('services.pagination.limit'));
        return ProjectResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }

    public function filterByStage(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $stage = $validated['stage'] ?? null;

        $query = Project::with('revisions')
            ->where('created_by', auth()->user()->id);

        // If stage is provided and not empty, apply filtering
        if (!empty($stage)) {
            $query->where('tss_stage', '!=', TssStage::Pending->value)
                ->where(function ($q) use ($stage) {
                    $q->where('tss_stage', $stage)
                        ->orWhere('marketing_stage', $stage);
                });
        }

        // Paginate always
        $data = $query->paginate(config('services.pagination.limit'));

        return ProjectResource::collection($data)->additional([
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
}
