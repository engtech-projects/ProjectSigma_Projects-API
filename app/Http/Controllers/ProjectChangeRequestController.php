<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectChangeRequest;
use App\Http\Requests\UpdateProjectChangeRequest;
use App\Http\Resources\ProjectChangeRequestResource;
use App\Models\ProjectChangeRequest;

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
        $changeRequest = ProjectChangeRequest::create($request->validated());
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

}
