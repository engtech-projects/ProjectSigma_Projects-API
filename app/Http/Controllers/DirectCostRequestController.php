<?php
namespace App\Http\Controllers;
use App\Enums\ChangeRequestType;
use App\Enums\ProjectStatus;
use App\Http\Resources\Project\ProjectLiveListingResource;
use App\Models\Project;
use App\Models\ProjectChangeRequest;
class DirectCostRequestController extends Controller
{
    public function index()
    {
        $data = Project::where('status', ProjectStatus::ONGOING->value)
            ->whereDoesntHave('changeRequests', function ($query) {
                $query->where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value);
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
        $data = ProjectChangeRequest::where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value)
            ->isPending()
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
        $data = ProjectChangeRequest::where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value)
            ->myRequests()
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
        $data = ProjectChangeRequest::where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value)
            ->myApprovals()
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
        $data = ProjectChangeRequest::where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value)
            ->where('request_status', "Approved")
            ->isApproved()
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectLiveListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
}
