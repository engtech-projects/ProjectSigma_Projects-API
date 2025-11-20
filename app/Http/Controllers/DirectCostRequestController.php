<?php
namespace App\Http\Controllers;
use App\Enums\ChangeRequestType;
use App\Enums\ProjectStatus;
use App\Enums\TssStatus;
use App\Http\Resources\Project\ProjectLiveListingResource;
use App\Models\Project;
use App\Models\ProjectChangeRequest;
class DirectCostRequestController extends Controller
{
    public function index()
    {
        $data = Project::where('status', ProjectStatus::ONGOING->value)
            ->where('tss_status', TssStatus::PENDING->value)
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
        $data = ProjectChangeRequest::directCostApproval()
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
        $data = ProjectChangeRequest::directCostApproval()
            ->myRequests()
            ->pendingTss()
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
        $data = ProjectChangeRequest::directCostApproval()
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
        $data = ProjectChangeRequest::with('project')
            ->directCostApproval()
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
