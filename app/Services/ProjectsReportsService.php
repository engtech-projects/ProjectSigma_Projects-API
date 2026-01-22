<?php
namespace App\Services;
use App\Models\Project;
use Carbon\Carbon;
class ProjectsReportsService
{
    public function fetchProjectUpdateReport($dateFrom, $dateTo)
    {
        $projects = Project::whereBetween('contract_date', [$dateFrom, $dateTo])
            ->get();
        $grouped = $projects->groupBy('implementing_office');
        return [
            'period_covered' => $dateFrom . ' to ' . $dateTo,
            'date' => now()->toDateString(),
            'area_region' => '',
            'implementing_office' => $grouped->map(function ($officeGroup, $officeName) {
                return [
                    'implementing_office' => $officeName ?? '',
                    'projects' => $officeGroup->map(function ($project) {
                        return [
                            'project_code' => $project->code ?? '',
                            'project_title' => $project->name ?? '',
                            'duration' => $project->duration ?? '',
                            'contract_amount' => [
                                'original' => $project->amount ?? '',
                                'revised' => null,
                            ],
                            'completion_date' => [
                                'original' => $project->contract_date ?? '',
                                'revised' => null,
                            ],
                            'project_owner_slippage' => null,
                            'accomplishment_percent_to_date' => null,
                            'billing_status_percent_to_date' => null,
                            'remarks' => null,
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    }
    public function fetchStewaReport() {}
    public function fetchAccomplishmentReport() {}
}
