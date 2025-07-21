<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Project\BoqItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PhaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'tasks' => BoqItemResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
