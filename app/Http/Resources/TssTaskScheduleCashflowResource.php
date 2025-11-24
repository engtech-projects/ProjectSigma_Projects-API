<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TssTaskScheduleCashflowResource extends JsonResource
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
            'name' => $this->name,
            'item_id' => $this->item_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'weight_percent' => $this->weight_percent,
            'resources' => $this->weightedResources,
        ];
    }
}
