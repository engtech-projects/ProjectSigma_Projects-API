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
    public function __invoke($modelType, $model)
    {
        $cacheKey = "approve" . $modelType . $model->id. '-'. Auth::user()->id;
        if (Cache::has($cacheKey)) {
            return new JsonResponse(["success" => false, "message" => "Too Many Attempts"], 429);
        }
        return Cache::remember($cacheKey, 5, function () use ($modelType, $model) {
            return $this->approve($modelType, $model);
        });
    }
    public function approve($modelType, $model)
    {
        $result = $model->updateApproval(['status' => RequestApprovalStatus::APPROVED, "date_approved" => Carbon::now()]);
        $nextApproval = $model->getNextPendingApproval();
        if ($nextApproval) {
            $notificationMap = [
                ApprovalModels::PROJECT_PROPOSAL_REQUEST->name => RequestProposalForApprovalNotification::class,
                ApprovalModels::PROJECT_CHANGE_REQUEST->name => ChangeRequestForApprovalNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $model->notifyNextApprover($notificationMap[$modelType]);
            }
        } else {
            $notificationMap = [
                ApprovalModels::PROJECT_PROPOSAL_REQUEST->name => RequestProposalForApprovedNotification::class,
                ApprovalModels::PROJECT_CHANGE_REQUEST->name => ChangeRequestApprovedNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $model->notifyCreator($notificationMap[$modelType]);
            }
        }
        return new JsonResponse(["success" => $result["success"], "message" => $result['message']], $result["status_code"]);
    }
}
