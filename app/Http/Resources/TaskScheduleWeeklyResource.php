<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskScheduleWeeklyResource extends JsonResource
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
            'task_schedule_id' => $this->task_schedule_id,
            'week_start_date' => $this->week_start_date,
            'week_end_date' => $this->week_end_date,
        ];
    }
}
