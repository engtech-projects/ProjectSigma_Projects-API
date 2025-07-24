<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class BoqPartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'tasks' => BoqItemResource::collection($this->whenLoaded('tasks')),
            'total_cost' => $this->tasks->sum('amount'),
        ];
    }
}
