<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'tasks' => ProjectTaskScheduleResource::collection($this->tasks),
            'schedules' => ProjectScheduleResource::collection($this->taskSchedules),
        ];
    }
}
