<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BomResource extends JsonResource
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
            'project_id' => $this->project_id,
            'task_id' => $this->task_id,
            'resource_id' => $this->resource_id,
            'material_name' => $this->material_name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'amount' => $this->amount,
            'additional_details' => $this->additional_details,
            'source_type' => $this->source_type,
        ];
    }
}
