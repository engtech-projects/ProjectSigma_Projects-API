<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectActivityResource extends JsonResource
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
            'item_id' => $this->item_id,
            'reference' => $this->reference,
            'quantity' => $this->quantity,
            'schedule' => $this->schedule,
            'work_description' => $this->work_description,
            'duration' => $this->duration,
            'target' => $this->target,
            'actual' => $this->actual,
            'total' => $this->total,
            'variance' => $this->variance,
            'balance_to_date' => $this->balance_to_date,
        ];
    }
}
