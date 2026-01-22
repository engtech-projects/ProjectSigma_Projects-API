<?php
namespace App\Http\Controllers;
use App\Enums\ProjectStatus;
use App\Enums\RequestStatus;
use App\Enums\TssStatus;
use App\Http\Resources\Project\ProjectChangeRequestListingResource;
use App\Http\Resources\Project\ProjectLiveListingResource;
use App\Models\Project;
use App\Models\ProjectChangeRequest;
use Symfony\Component\HttpFoundation\Request;
class BillOfMaterialsController extends Controller
{
    public function index()
    {
        $data = Project::where('status', ProjectStatus::ONGOING->value)
            ->where('bom_status', RequestStatus::PENDING->value)
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
            ->whereNotApproved()
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectChangeRequestListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
    public function myRequests()
    {
        $data = ProjectChangeRequest::directCostApproval()
            ->myRequests()
            ->ongoingTss()
            ->with('project')
            ->latest('created_at')
            ->paginate(config('services.pagination.limit'));
        return ProjectChangeRequestListingResource::collection($data)
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
        return ProjectChangeRequestListingResource::collection($data)
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
        return ProjectChangeRequestListingResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Successfully fetched.',
            ]);
    }
}
