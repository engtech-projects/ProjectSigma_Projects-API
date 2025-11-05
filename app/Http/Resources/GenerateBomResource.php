<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenerateBomResource extends JsonResource
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
            'project_id' => $this->project_id ?? $this->resource->task->phase->project_id,
            'task_id' => $this->task_id,
            'resource_id' => $this->resource_id ?? $this->resource->id,
            'item' => $this->setupItemProfile->item_description ?? 'Missing Setup Item Profile.',
            'unit' => $this->unit,
            'original_quantity' => $this->quantity,
        ];
    }
}
