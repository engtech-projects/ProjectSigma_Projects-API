<?php

namespace App\Http\Resources\Phase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Task\TaskResource;

class PhaseResource extends JsonResource
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
			'name' => $this->name,
			'description' => $this->description,
			'total_cost' => $this->total_cost,
			'tasks' => TaskResource::collection($this->tasks),
		];
    }
}
