<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalModels;
use App\Enums\RequestStatuses;
use App\Http\Requests\VoidApprovalRequest;
use App\Notifications\ChangeRequestVoidedNotification;
use App\Notifications\RequestProposalVoidedNotification;
use Carbon\Carbon;

class VoidApproval extends Controller
{
    public function __invoke($modelType, $model, VoidApprovalRequest $request)
    {
        $attribute = $request->validated();
        $result = collect($model->updateApproval([
            "status" => RequestStatuses::VOID->value,
            "remarks" => $attribute['remarks'],
            "date_voided" => Carbon::now(),
        ]));
        switch ($modelType) {
            case ApprovalModels::PROJECT_PROPOSAL_REQUEST->name:
                $model->created_by_user->notify(new RequestProposalVoidedNotification($request->bearerToken(), $model));
                break;
            case ApprovalModels::PROJECT_CHANGE_REQUEST->name:
                $model->created_by_user->notify(new ChangeRequestVoidedNotification($request->bearerToken(), $model));
                break;
            default:
                break;
        }
        return response()->json([
            "success" => $result["success"],
            "message" => $result['message']
        ], $result["status_code"]);
    }
}
