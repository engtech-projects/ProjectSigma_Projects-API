<?php

namespace App\Http\Controllers;

use App\Http\Requests\CumulativeBillingRequest;
use App\Http\Resources\CumulativeBillingResource;
use App\Http\Requests\CurrentMonthBillingRequest;
use App\Http\Requests\ProjectedProgressBillingRequest;
use App\Http\Requests\TotalBilledAndBalanceToBeBilledRequest;
use App\Http\Resources\CurrentMonthBillingResource;
use App\Http\Resources\TotalBilledBalanceToBeBilledResource;
use App\Services\ProjectsBillingService;
use App\Http\Resources\ProjectedProgressBillingResource;

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
                'message' => 'Billing summary loaded successfully',
                'status' => 'success',
                'original_contract_amount_grand_total' => $result['original_contract_amount_grand_total'],
            ]);
    }
    public function getCumulativeBilling(CumulativeBillingRequest $request)
    {
        $validated = $request->validated();
        $result = $this->billingService->getCumulativeBilling(
            $validated['selected_year'],
            $validated['as_of_month'],
            $validated['as_of_year']
        );
        return CumulativeBillingResource::collection($result['grouped_projects'])
            ->additional([
                'message' => 'Cumulative billing loaded successfully',
                'status'  => 'success',
            ]);
    }
    public function getCurrentMonthBilling(CurrentMonthBillingRequest $request)
    {
        $validated = $request->validated();
        $result = $this->billingService->getCurrentMonthBilling(
            $validated['selected_month'],
            $validated['selected_year']
        );
        return CurrentMonthBillingResource::collection($result['projects'])->additional([
            'status' => 'success',
            'message' => 'Current month billing loaded successfully',
            'gross_total' => $result['gross_total'],
        ]);
    }
    public function getProjectedProgressBilling(ProjectedProgressBillingRequest $request)
    {
        $validated = $request->validated();
        $result = $this->billingService->getProjectedProgressBilling(
            $validated['selected_month'],
            $validated['selected_year']
        );
        return ProjectedProgressBillingResource::collection($result['projects'])->additional([
            'status' => 'success',
            'message' => 'Projected progress billing loaded successfully',
            'net_total' => $result['net_total'],
        ]);
    }
}
