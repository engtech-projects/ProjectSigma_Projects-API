<?php

namespace App\Http\Resources\ResourceItem;

use App\Http\Resources\ResourceName\ResourceNameCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceItemCollection extends JsonResource
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
            'project_id' => $this->project_id,
            'task_id' => $this->task_id,
            'resources' => $this->whenLoaded('resourceName', function ($resourceName) {
                return new ResourceNameCollection($resourceName);
            }),
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
