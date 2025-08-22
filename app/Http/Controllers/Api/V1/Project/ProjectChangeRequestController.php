<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectChangeRequestResource;
use App\Models\ProjectChangeRequest;
use Illuminate\Http\Request;

class ProjectChangeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listOfProjectChangeRequests = ProjectChangeRequest::all();
        return ProjectChangeRequestResource::collection($listOfProjectChangeRequests)
            ->additional([
                'success' => true,
                'message' => 'Project change requests retrieved successfully',
            ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = [
            'project_id' => 'required|exists:projects,id',
            'title' => 'nullable',
            'description' => 'nullable',
            'request_type' => 'required',
            'changes' => 'required',
            'status' => 'required',
        ];
        $projectChangeRequest = ProjectChangeRequest::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Project change request created successfully',
            'data' => $projectChangeRequest,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
