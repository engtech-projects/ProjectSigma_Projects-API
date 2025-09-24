<?php

namespace App\Http\Resources;

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryOfDirectEstimateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->id,
            'pay_item_no.' => $this->name,
            'description' => $this->description,
            'unit_price' => $this->item_unit_price,
            'contract_cost' => $this->contract_cost,
            'direct_cost' => $this->resource_items,
            'total' => $this->resource_items_total,
            'unit_cost_per_item' => $this->unit_cost_per_item,
            'percent' => $this->percent,
        ];
    }
}
