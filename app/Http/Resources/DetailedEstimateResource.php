<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedEstimateResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'setup_item_profile_id' => $this->setup_item_profile_id,
            'project_id' => $this->project_id,
            'task_id' => $this->task_id,
            'description' => $this->description,
            'unit_count' => $this->unit_count,
            'formatted_unit_count' => $this->formatted_unit_count,
            'quantity' => $this->quantity,
            'formatted_quantity' => $this->formatted_quantity,
            'unit' => $this->unit,
            'unit_name' => $this->unit_name,
            'unit_cost' => $this->unit_cost,
            'formatted_unit_cost' => $this->formatted_unit_cost,
            'resource_count' => $this->resource_count,
            'total_cost' => $this->total_cost,
            'formatted_total_cost' => $this->formatted_total_cost,
            'resource_name' => $this->resource_type->displayName(),
            'resource_type' => $this->resource_type
        ];
    }
}
