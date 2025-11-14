<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCompletionReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "project_id" => $this->project_id,
            "project_code" => $this->project_code,
            "scope_of_work" => $this->scope_of_work,
            "agency" => $this->agency,
            "date_of_mobilization" => $this->date_of_mobilization,
            "project_data" => [
                "contact_cost_original" => $this->contract_cost_original,
                "contract_duration_original" => $this->contract_duration_original,
                "scheduled_completion_date_original" => $this->scheduled_completion_date_original,
                "contract_cost_revised" => $this->contract_cost_revised,
                "contract_duration_revised" => $this->contract_duration_revised,
                "scheduled_completion_date_revised" => $this->scheduled_completion_date_revised,
            ],
            "project_performance_measures" => [
                "completion_date_target" => $this->completion_date_target,
                "project_cost_total_target" => $this->project_cost_total_target,
                "materials_target" => $this->materials_target,
                "equipment_target" => $this->equipment_target,
                "labor_target" => $this->labor_target,
                "fuel_oil_target" => $this->fuel_oil_target,
                "overhead(direct)_target" => $this->overhead_target, 
                "completion_date_actual" => $this->completion_date_actual,
                "project_cost_total_actual" => $this->project_cost_total_actual,
                "materials_actual" => $this->materials_actual,
                "equipment_actual" => $this->equipment_actual,
                "labor_actual" => $this->labor_actual,
                "fuel_oil_actual" => $this->fuel_oil_actual,
                "overhead(direct)_actual" => $this->overhead_actual,
                "completion_date_variance" => $this->completion_date_variance,
                "project_cost_total_variance" => $this->project_cost_total_variance,
                "materials_variance" => $this->materials_variance,
                "equipment_variance" => $this->equipment_variance,
                "labor_variance" => $this->labor_variance,
                "fuel_oil_variance" => $this->fuel_oil_variance,
                "overhead(direct)_variance" => $this->overhead_variance,   
                "number_of_accidents" => $this->number_of_accidents,
            ], 
        ];
    }
}
