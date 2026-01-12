<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestApprovalStatus;
use App\Http\Controllers\Controller;
use App\Notifications\ChangeRequestApprovedNotification;
use App\Notifications\ChangeRequestForApprovalNotification;
use App\Notifications\RequestProposalForApprovalNotification;
use App\Notifications\RequestProposalForApprovedNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ApproveApproval extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, Request $request)
    {
        $cacheKey = "approve" . $modelType . $model->id. '-'. Auth::user()->id;
        if (Cache::has($cacheKey)) {
            return new JsonResponse(["success" => false, "message" => "Too Many Attempts"], 429);
        }
        return Cache::remember($cacheKey, 5, function () use ($modelType, $model, $request) {
            return $this->approve($modelType, $model, $request);
        });
    }
    public function approve($modelType, $model, $request)
    {
        $result = $model->updateApproval(["status" => RequestApprovalStatus::APPROVED, "date_approved" => Carbon::now()]);
        $nextApproval = $model->getNextPendingApproval();
        if ($nextApproval) {
            $nextApprover = User::find($nextApproval['user_id']);
            $notificationMap = [
                ApprovalModels::PROJECT_PROPOSAL_REQUEST->name => RequestProposalForApprovalNotification::class,
                ApprovalModels::PROJECT_CHANGE_REQUEST->name => ChangeRequestForApprovalNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $nextApprover->notify(new $notificationMap[$modelType]($request->bearerToken(), $model));
            }
        } else {
            $notificationMap = [
                ApprovalModels::PROJECT_PROPOSAL_REQUEST->name => RequestProposalForApprovedNotification::class,
                ApprovalModels::PROJECT_CHANGE_REQUEST->name => ChangeRequestApprovedNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $model->created_by_user->notify(new $notificationMap[$modelType]($request->bearerToken(), $model));
            }
        }
        return new JsonResponse(["success" => $result["success"], "message" => $result['message']], $result["status_code"]);
    }
}
