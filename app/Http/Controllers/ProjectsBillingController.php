<?php

namespace App\Http\Controllers;

use App\Http\Requests\TotalBilledAndBalanceToBeBilledRequest;
use App\Http\Resources\TotalBilledBalanceToBeBilledResource;
use App\Models\Project;
use App\Services\ProjectsBillingService;

class ProjectsBillingController extends Controller
{
    protected ProjectsBillingService $billingService;

    public function __construct(ProjectsBillingService $billingService)
    {
        $this->billingService = $billingService;
    }
    public function getTotalBilledAndBalanceToBeBilled(Project $project, TotalBilledAndBalanceToBeBilledRequest $request)
    {
        $validated = $request->validated();
        $result = $this->billingService->getTotalBilledAndBalanceToBeBilled(
            $validated['year'],
            $validated['as_of_month'],
            $validated['as_of_year']
        );
        return TotalBilledBalanceToBeBilledResource::collection($result['projects'])
            ->additional([
                'message' => 'Billing summary retrieved successfully',
                'status' => 'success',
                'original_contract_amount_grand_total' => $result['original_contract_amount_grand_total'],
            ]);
    }
}
