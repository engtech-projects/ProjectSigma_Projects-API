<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectChangeRequest;
use App\Http\Requests\UpdateProjectChangeRequest;
use App\Http\Resources\ProjectChangeRequestResource;
use App\Models\ProjectChangeRequest;
use Illuminate\Http\Request;


class ProjectChangeRequestController extends Controller
{
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

    public function store(StoreProjectChangeRequest $request)
    {
        $projectChangeRequest = ProjectChangeRequest::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Project change request created successfully',
            'data' => $projectChangeRequest,
        ], 201);
    }

    public function update(UpdateProjectChangeRequest $request, $id)
    {
        $projectChangeRequest = ProjectChangeRequest::update($id, $request->validated());
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
