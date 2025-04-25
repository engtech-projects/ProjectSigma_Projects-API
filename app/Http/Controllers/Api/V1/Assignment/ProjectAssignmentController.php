<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectAssignment\StoreProjectAssignmentRequest;
use App\Http\Requests\ProjectAssignment\UpdateProjectAssignmentRequest;
use App\Http\Resources\ProjectAssignment\ProjectAssignmentCollection;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectAssignmentController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Project $project)
    {
        $team = ProjectAssignment::where('project_id', $project->id)->get();

        return response()->json(ProjectAssignmentCollection::collection($team), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectAssignmentRequest $request)
    {
        $validated = $request->validated();
        $project = Project::find($request->project_id);
        $projectTeam = $this->projectService->assignTeam(
            $project,
            $request->project_assignments
        );

        return response()->json($projectTeam, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, ProjectAssignment $projectAssignment)
    {
        return response()->json(ProjectAssignmentCollection::collection($projectAssignment), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectAssignmentRequest $request)
    {
        $validated = $request->validated();
        $project = Project::find($request->project_id);
        $projectTeam = $this->projectService->assignTeam(
            $project,
            $request->project_assignments
        );

        return response()->json($projectTeam, 200);
    }
}
