<?php

namespace App\Http\Resources;

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
                'id' => $this->id,
                'title' => $this->name,
                'phases' => $this->phases->map(function ($phase){
                    return [
                        'phase_id' => $phase->id,
                        'name' => $phase->name,
                        'tasks' => $phase->tasks->map(function ($task) {
                            return [
                                'task_id' => $task->id,
                                'name' => $task->name,
                                'schedules' => $task->schedules->map(function ($schedule) {
                                    return [
                                        'id' => $schedule->id,
                                        'name' => $schedule->name,
                                        'original_start' => $schedule->original_start,
                                        'original_end' => $schedule->original_end,
                                        'current_start' => $schedule->current_start,
                                        'current_end' => $schedule->current_end,
                                        'duration_days' => $schedule->duration_days,
                                        'weight_percent' => $schedule->weight_percent,
                                        'status' => $schedule->status,
                                    ];
                                })
                            ];
                        })
                    ];
                })
            ]
        ];
    }
}
