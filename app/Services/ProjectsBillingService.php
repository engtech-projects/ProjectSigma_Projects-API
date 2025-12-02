<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;

class ProjectsBillingService
{
    public function getTotalBilledAndBalanceToBeBilled(int $year, int $asOfMonth, int $asOfYear)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfDay();
        $endDate = Carbon::create($asOfYear, $asOfMonth, 1)->endOfMonth()->endOfDay();
        $projects = Project::select('id', 'code', 'name', 'location', 'amount', 'ntp_date')
            ->whereBetween('ntp_date', [$startDate, $endDate])
            ->orderBy('ntp_date', 'asc')
            ->get();
        $originalContractTotalAmount = $projects->sum('amount');
        return [
            'projects' => $projects,
            'original_contract_total_amount' => $originalContractTotalAmount,
        ];
    }
}
