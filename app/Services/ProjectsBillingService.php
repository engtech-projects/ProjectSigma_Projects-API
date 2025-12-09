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
            'original_contract_amount_grand_total' => $originalContractTotalAmount,
        ];
    }
    public function getCumulativeBilling(int $selectedYear, int $asOfMonth, int $asOfYear)
    {
        $startDate = Carbon::create($selectedYear, 1, 1)->startOfDay();
        $endDate   = Carbon::create($asOfYear, $asOfMonth, 1)->endOfMonth()->endOfDay();
        $projects = Project::select('id', 'code', 'name', 'location', 'amount', 'ntp_date')
            ->whereBetween('ntp_date', [$startDate, $endDate])
            ->orderBy('ntp_date', 'asc')
            ->get();
        $grouped = $projects->groupBy(function ($proj) {
            return Carbon::parse($proj->ntp_date)->format('Y-m');
        });
        $groupedProjects = [];
        $runningTotal = 0;
        foreach ($grouped as $ym => $items) {
            $total = $items->sum('amount');
            $runningTotal += $total;
            $groupedProjects[] = [
                'year_month'       => $ym,
                'total_amount'     => $total,
                'cumulative_total' => $runningTotal,
                'projects'         => $items,
            ];
        }
        return [
            'grouped_projects' => $groupedProjects
        ];
    }
    public function getCurrentMonthBilling($month, $year)
    {
        $projects = Project::select('id', 'code', 'name', 'location', 'amount', 'ntp_date')
            ->whereMonth('ntp_date', $month)
            ->whereYear('ntp_date', $year)
            ->orderBy('ntp_date', 'asc')
            ->get();
        $gross_total = $projects->sum('amount');
        return [
            'projects' => $projects,
            'gross_total' => $gross_total,
        ];
    }
    public function getProjectedProgressBilling($month, $year)
    {
        $projects = Project::select('id', 'code', 'name', 'location', 'amount', 'ntp_date')
            ->whereMonth('ntp_date', $month)
            ->whereYear('ntp_date', $year)
            ->orderBy('ntp_date', 'asc')
            ->get();
        $net_total = $projects->sum('amount');
        return [
            'projects' => $projects,
            'net_total' => $net_total,
        ];
    }
}
