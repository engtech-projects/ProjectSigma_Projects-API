<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummarizedBoqItemResource extends JsonResource
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
            'phase_id' => $this->phase_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->formatted_quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'draft_unit_price' => $this->draft_unit_price,
            'draft_amount' => $this->draft_amount,
            'amount' => $this->amount,
            'resources' => ResourceItemResource::collection($this->whenLoaded('resources')),
        ];
    }
}
