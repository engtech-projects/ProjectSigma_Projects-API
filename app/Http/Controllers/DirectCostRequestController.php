<?php

namespace App\Http\Controllers;

use App\Http\Resources\Project\ProjectLiveListingResource;
use App\Models\Project;
use App\Models\ProjectChangeRequest;

class DirectCostRequestController extends Controller
{
    public function index()
    {
        $data = Project::where('status', 'ongoing')
            ->whereDoesntHave('changeRequests', function ($query) {
                $query->where('request_type', 'directcost_approval_request');
            })
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
    public function allRequests()
    {
        $data = ProjectChangeRequest::where('request_type', "directcost_approval")
            ->where('request_status', "Pending")
            ->with('project')
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
    public function myRequests()
    {
        $userId = auth()->id();
        $data = ProjectChangeRequest::where('request_type', "directcost_approval")
            ->where('request_status', "Pending")
            ->where('created_by', $userId)
            ->with('project')
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
    public function myApprovals()
    {
        $userId = auth()->id();
        $data = ProjectChangeRequest::where('request_type', "directcost_approval")
            ->where('request_status', "Pending")
            ->whereJsonContains('approvals', ['user_id' => $userId])
            ->with('project')
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
    public function approved()
    {
        $data = ProjectChangeRequest::where('request_type', "directcost_approval")
            ->where('request_status', "Approved")
            ->with('project')
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
}
