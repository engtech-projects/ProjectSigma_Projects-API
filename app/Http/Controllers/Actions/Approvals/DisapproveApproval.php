<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisapproveApprovalRequest;
use App\Notifications\RequestProposalForDeniedNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DisapproveApproval extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, DisapproveApprovalRequest $request)
    {
        $attribute = $request->validated();
        $result = collect($model->updateApproval([
            'status' => RequestApprovalStatus::DENIED,
            'remarks' => $attribute['remarks'],
            'date_denied' => Carbon::now(),
        ]));

        switch ($modelType) {
            case ApprovalModels::PROJECT_PROPOSAL_REQUEST->name:
                $model->notify(new RequestProposalForDeniedNotification(auth()->user()->token, $model));
                break;
            default:
                break;
        }

        return new JsonResponse(['success' => $result['success'], 'message' => $result['message']], JsonResponse::HTTP_OK);
    }
}
