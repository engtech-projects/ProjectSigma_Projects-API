<?php

namespace App\Http\Resources\Phase;

use App\Http\Resources\Task\TaskCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhaseCollection extends JsonResource
{
    public static $wrap = 'phases';

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
            'name' => $this->name,
            'description' => $this->description,
            'total_cost' => $this->total_cost,
            'tasks' => TaskCollection::make($this->whenLoaded('tasks')),
        ];
    }
}
