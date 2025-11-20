<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class TaskScheduleResource extends JsonResource
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
            'timeline_classification' => $this->timeline_classification,
            'item_id' => $this->item_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'duration_days' => $this->duration_days,
            'weight_percent' => $this->weight_percent,
            'status' => $this->status,
            'weeks' => TaskScheduleWeeksResource::collection($this->whenLoaded('weeks')),
        ];
    }
}
