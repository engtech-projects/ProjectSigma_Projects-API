<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\ResourceItem\ResourceItemCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCollection extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'phase_id' => $this->phase_id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price,
            'amount' => $this->amount,
            'total_price' => $this->total_price,
            'unit_price_with_quantity' => $this->unit_price_with_quantity,
            'resources' => $this->whenLoaded('resources', function() {
                return [
                    'ocm' => $this->ocm,
                    'contractors_profit' => $this->contractors_profit,
                    'vat' => $this->vat,
                    'grand_total' => $this->grand_total,
                    'resources_item_total' => $this->resource_item_total,
                    'unit_cost_per' => $this->unit_cost_per,
                    'data' => ResourceItemCollection::collection($this->resources),
                ];
            }),
        ];
    }
}
