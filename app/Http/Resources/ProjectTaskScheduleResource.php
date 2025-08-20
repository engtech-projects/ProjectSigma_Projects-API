<?php

namespace App\Http\Resources;

use App\Http\Resources\PhasesDetailedResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectTaskScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project' => [
                'title' => $this->name,
                'phases' => PhasesDetailedResource::collection($this->whenLoaded('phases')),
            ]
        ];
    }
}
