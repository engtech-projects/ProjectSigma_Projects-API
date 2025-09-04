<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\DetailedEstimateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BoqItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project->id,
            'project_contract_id' => $this->project->contract_id,
            'project_name' => $this->project->name,
            'project_location' => $this->project->location,
            'project_created_at' => $this->project->created_at_formatted,
            'project_created_by' => $this->project->created_by,
            'project_license' => $this->project->license,
            'phase_id' => $this->phase_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'amount' => $this->amount,
            'total_materials_amount' => $this->total_materials_amount,
            'ocm' => $this->ocm,
            'resource_totals' => $this->resource_totals,
            'direct_cost' => $this->total_direct_cost,
            'contractors_profit' => $this->contractors_profit,
            'vat' => $this->vat,
            'grand_total' => $this->grand_total,
            'unit_cost_per' => $this->unit_cost_per,
            'total_equipment_amount' => $this->total_equipment_amount,
            'total_labor_amount' => $this->total_labor_amount,
            'total_fuel_oil_amount' => $this->total_fuel_oil_amount,
            'total_overhead_amount' => $this->total_overhead_amount,
            'total_price' => $this->total_price,
            'unit_price_with_unit' => $this->unit_price_with_unit,
            'resources' => DetailedEstimateResource::collection($this->whenLoaded('resources')),
        ];
    }
}
