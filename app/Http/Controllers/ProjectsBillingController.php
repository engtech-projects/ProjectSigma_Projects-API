<?php

namespace App\Http\Controllers;

use App\Http\Requests\CumulativeBillingRequest;
use App\Http\Requests\TotalBilledAndBalanceToBeBilledRequest;
use App\Http\Resources\CumulativeBillingResource;
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
    public function getTotalBilledAndBalanceToBeBilled(TotalBilledAndBalanceToBeBilledRequest $request)
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
    public function getCumulativeBilling(CumulativeBillingRequest $request)
    {
        $validated = $request->validated();
        $result = $this->billingService->getCumulativeBilling(
            $validated['year'],
            $validated['as_of_month'],
            $validated['as_of_year']
        );
        return CumulativeBillingResource::collection($result['projects'])
            ->additional([
                'message' => 'Billing summary retrieved successfully',
                'status' => 'success',
                'original_contract_amount_grand_total' => $result['original_contract_amount_grand_total'],
            ]);
    }
}
