<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalModels;
use App\Enums\RequestStatuses;
use App\Http\Requests\CancelApprovalRequest;
use App\Notifications\ChangeRequestCancelledNotification;
use App\Notifications\RequestProposalCancelledNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CancelApproval extends Controller
{
    public function __invoke($modelType, $model, CancelApprovalRequest $request)
    {
        $attributes = $request->validated();
        $result = collect($model->cancelApproval([
            "status" => RequestStatuses::CANCELLED->value,
            "remarks" => $attributes["remarks"],
            "date_cancelled" => Carbon::now(),
        ]));
        switch ($modelType) {
            case ApprovalModels::PROJECT_PROPOSAL_REQUEST->name:
                $model->created_by_user->notify(new RequestProposalCancelledNotification($request->bearerToken(), $model));
                break;
            case ApprovalModels::PROJECT_CHANGE_REQUEST->name:
                $model->created_by_user->notify(new ChangeRequestCancelledNotification($request->bearerToken(), $model));
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
