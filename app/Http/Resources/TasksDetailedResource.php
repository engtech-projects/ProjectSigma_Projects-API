<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksDetailedResource extends JsonResource
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
            'item_no' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'formatted_quantity' => $this->formatted_quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_cost_per,
            'draft_unit_price' => $this->draft_unit_price,
            'draft_amount' => $this->draft_amount,
            'amount' => $this->amount,
            'relative_weight' => $this->relative_weight,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'duration' => $this->duration,
            'schedules' =>  $this->schedule ? TaskScheduleResource::collection(collect([$this->schedule])) : [],
        ];
    }
}
