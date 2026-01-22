<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProjectReportUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'period_covered' => $this->period_covered ?? '',
            'date' => $this->date ?? '',
            'area_region' => $this->area_region ?? '',
            'implementing_office' => $this->implementingOffices->map(function ($office) {
                return [
                    'implementing_office' => $office->name ?? '',
                    'projects' => $office->projects->map(function ($project) {
                        return [
                            'project_code' => $project->project_code ?? '',
                            'project_title' => $project->project_title ?? '',
                            'duration' => $project->duration ?? '',
                            'contract_amount' => [
                                'original' => $project->original_contract_amount ?? '',
                                'revised' => $project->revised_contract_amount ?? '',
                            ],
                            'completion_date' => [
                                'original' => $project->original_completion_date ?? '',
                                'revised' => $project->revised_completion_date ?? '',
                            ],
                            'project_owner_slippage' => $project->project_owner_slippage ?? '',
                            'accomplishment_percent_to_date' => $project->accomplishment_percent_to_date ?? '',
                            'billing_status_percent_to_date' => $project->billing_status_percent_to_date ?? '',
                            'remarks' => $project->remarks ?? '',
                        ];
                    }),
                ];
            }),
        ];
    }
}
