<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\DetailedEstimateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BoqItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phase_id' => $this->phase_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'amount' => $this->amount,
            'total_price' => $this->total_price,
            'unit_price_with_quantity' => $this->unit_price_with_quantity,
            'resources' => DetailedEstimateResource::collection($this->whenLoaded('resources')),
        ];
    }
}
