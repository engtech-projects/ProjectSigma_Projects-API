<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DupaDetailedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'name_id' => $this->name_id,
            'description' => $this->description,
            'unit_count' => $this->unit_count,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_cost' => $this->unit_cost,
            'resource_count' => $this->resource_count,
            'total_cost' => $this->total_cost,
        ];
    }
}
