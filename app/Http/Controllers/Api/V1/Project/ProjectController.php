<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\FilterProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\SummaryRate\SummaryRateRequest;
use App\Http\Resources\Project\ProjectCollection;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

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
        $validatedData = $request->validated();
        $projects = $this->projectService->withPagination($validatedData);

        return response()->json($projects, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function original(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort']);

        $projects = Project::query()
            ->original()
            ->filter($filters)
            ->retrieve(true, $request->per_page);

        return response()->json(new ProjectCollection($projects), 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function revised(Request $request)
    {

        $filters = $request->only(['search', 'status', 'sort']);

        $projects = Project::query()
            ->revised()
            ->filter($filters)
            ->retrieve(true, $request->per_page);

        return response()->json(new ProjectCollection($projects), 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function proposal(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort']);

        $projects = Project::query()
            ->proposal()
            ->filter($filters)
            ->retrieve(true, $request->per_page);

        return response()->json(new ProjectCollection($projects), 200);
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
        return response()->json(new ProjectCollection($project->load('phases.tasks')), 200);
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

    public function archive(Project $project) {}

    public function destroy(Project $project) {}
}
