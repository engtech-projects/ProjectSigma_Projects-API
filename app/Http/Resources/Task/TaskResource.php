<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ResourceItem\ResourceItemResource;

class TaskResource extends JsonResource
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
			'phase_id' => $this->phase_id,
			'name' => $this->name,
			'description' => $this->description,
			'quantity' => $this->quantity,
			'unit' => $this->unit,
			'unit_price' => $this->unit_price,
			'amount' => $this->amount,
            'resources' => ResourceItemResource::collection($this->resources),  
		];
    }
}
