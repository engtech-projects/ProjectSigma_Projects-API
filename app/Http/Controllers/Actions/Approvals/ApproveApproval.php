<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestApprovalStatus;
use App\Http\Controllers\Controller;
use App\Notifications\RequestProposalForApprovalNotification;
use App\Notifications\RequestProposalForApprovedNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApproveApproval extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, Request $request)
    {
        $result = $model->updateApproval([
            'status' => RequestApprovalStatus::APPROVED,
            'date_approved' => Carbon::now(),
        ]);

        $nextApproval = $model->getNextPendingApproval();
        if ($nextApproval) {
            switch ($modelType) {
                case ApprovalModels::PROJECT_PROPOSAL_REQUEST->name:
                    $model->notify(new RequestProposalForApprovalNotification(auth()->user()->token, $model));
                    break;
                default:
                    break;
            }
        } else {
            switch ($modelType) {
                case ApprovalModels::PROJECT_PROPOSAL_REQUEST->name:
                    $model->notify(new RequestProposalForApprovedNotification(auth()->user()->token, $model));
                    break;
                default:
                    break;
            }
        }

        return new JsonResponse(['success' => $result['success'], 'message' => $result['message']], $result['status_code']);
    }
}
