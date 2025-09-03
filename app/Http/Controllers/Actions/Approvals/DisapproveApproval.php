<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisapproveApprovalRequest;
use App\Notifications\ChangeRequestDeniedNotification;
use App\Notifications\RequestProposalForDeniedNotification;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DisapproveApproval extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, DisapproveApprovalRequest $request)
    {
        $cacheKey = "disapprove" . $modelType . $model->id . '-' . Auth::user()->id;
        if (Cache::has($cacheKey)) {
            return new JsonResponse(["success" => false, "message" => "Too Many Attempts"], 429);
        }
        return Cache::remember($cacheKey, 5, function () use ($modelType, $model, $request) {
            return $this->disapprove($modelType, $model, $request);
        });
    }
    public function disapprove($modelType, $model, DisapproveApprovalRequest $request)
    {
        $attribute = $request->validated();
        $result = collect($model->updateApproval([
            "status" => RequestApprovalStatus::DENIED,
            "remarks" => $attribute['remarks'],
            "date_denied" => Carbon::now(),
        ]));
        $notificationMap = [
            ApprovalModels::PROJECT_PROPOSAL_REQUEST->name => RequestProposalForDeniedNotification::class,
            ApprovalModels::PROJECT_CHANGE_REQUEST->name => ChangeRequestDeniedNotification::class,
        ];
        if (isset($notificationMap[$modelType])) {
            $model->created_by_user->notify(new $notificationMap[$modelType]($request->bearerToken(), $model));
        }
        return response()->json([
            "success" => $result["success"],
            "message" => $result['message']
        ], $result["status_code"]);
    }
}
