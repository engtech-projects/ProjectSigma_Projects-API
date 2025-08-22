<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectChangeRequestResource;
use App\Models\ProjectChangeRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;


class ProjectChangeRequestController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    public function index()
    {
        $projectChangeRequest = ProjectChangeRequest::all();
        return response()->json([
            'success' => true,
            'message' => 'Project change requests retrieved successfully',
            'data' => ProjectChangeRequestResource::collection($projectChangeRequest),
        ], 200);
    }

    public function show($id)
    {
        $projectChangeRequest = ProjectChangeRequest::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Project change request retrieved successfully',
            'data' => $projectChangeRequest,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'requested_by' => 'required|exists:users,id',
            'request_type' => 'required|in:scoped_change,deadline_extension,budget_adjustment',
            'changes' => 'nullable|json',
            'status' => 'required|in:pending,approved,declined',
        ]);
        $projectChangeRequest = $this->projectService->createProjectChangeRequest($validated);
        return response()->json([
            'success' => true,
            'message' => 'Project change request created successfully',
            'data' => $projectChangeRequest,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:pending,approved,declined',
        ]);
        $projectChangeRequest = $this->projectService->updateProjectChangeRequest($id, $validated);
        return response()->json([
            'success' => true,
            'message' => 'Project change request updated successfully',
            'data' => $projectChangeRequest,
        ], 200);
    }

    public function destroy($id)
    {
        $projectChangeRequest = ProjectChangeRequest::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Project change request deleted successfully',
            'data' => $projectChangeRequest,
        ], 200);
    }

}
