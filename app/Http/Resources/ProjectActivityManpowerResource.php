<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectActivityManpowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "description" => $this->description,
            "unit_count" => $this->unit_count,
            "quantity" => $this->quantity,
            "unit" => $this->unit,
            "unit_name" => $this->unit_name,
            "unit_cost" => $this->unit_cost,
            "resource_count" => $this->resource_count,
        ];
    }
}
