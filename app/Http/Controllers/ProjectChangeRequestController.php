<?php

namespace App\Http\Controllers;

use App\Enums\TssStatus;
use App\Http\Requests\StoreProjectChangeRequest;
use App\Http\Requests\UpdateProjectChangeRequest;
use App\Http\Resources\ProjectChangeRequestResource;
use App\Models\Project;
use App\Models\ProjectChangeRequest;
use App\Services\ProjectService;

class ProjectChangeRequestController extends Controller
{
    public function index()
    {
        $changeRequests = ProjectChangeRequest::all();
        return response()->json([
            'success' => true,
            'message' => 'Project change requests retrieved successfully',
            'data' => ProjectChangeRequestResource::collection($changeRequests),
        ], 200);
    }
    public function store(StoreProjectChangeRequest $request)
    {
        $project = Project::with('phases.tasks.resources')->findOrFail($request->project_id);
        $projectService = new ProjectService($project);
        $unlinkedMaterials = $projectService->hasUnlinkedMaterials();
        if ($unlinkedMaterials) {
            return response()->json([
                'success' => false,
                'message' => 'Some material resources are not yet connected to IMS.',
                'unlinked_materials' => $unlinkedMaterials,
            ], 422);
        }
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $project->tss_status = TssStatus::ONGOING->value;
        $project->save();
        $changeRequest = ProjectChangeRequest::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Project change request created successfully',
            'data' => new ProjectChangeRequestResource($changeRequest),
        ], 201);
    }
    public function show(ProjectChangeRequest $changeRequest)
    {
        return response()->json([
            'success' => true,
            'message' => 'Project change request retrieved successfully',
            'data' => new ProjectChangeRequestResource($changeRequest),
        ], 200);
    }
    public function update(UpdateProjectChangeRequest $request, ProjectChangeRequest $changeRequest)
    {
        $changeRequest->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Project change request updated successfully',
            'data' => new ProjectChangeRequestResource($changeRequest),
        ], 200);
    }
    public function destroy(ProjectChangeRequest $changeRequest)
    {
        $changeRequest->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project change request deleted successfully',
            'data' => new ProjectChangeRequestResource($changeRequest),
        ], 200);
    }
    public function restore($changeRequest)
    {
        $deletedChangeRequest = ProjectChangeRequest::withTrashed()->findOrFail($changeRequest);
        $deletedChangeRequest->restore();
        return response()->json([
            'success' => true,
            'message' => 'Project change request restored successfully',
        ], 200);
    }
}
