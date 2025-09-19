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
        $total = $this->resources->sum('total_cost');
        $unitCostPerItem = $this->quantity > 0 ? $total / $this->quantity : 0;
        $percent = ($this->quantity > 0 && $this->unit_price > 0)
            ? ($unitCostPerItem / $this->unit_price) * 100
            : 0;

        return [
            'item_id' => $this->id,
            'pay_item_no.' => $this->name,
            'description' => $this->description,
            'unit_price' => $this->item_unit_price,
            'contract_cost' => $this->contract_cost,
            'direct_cost' => $this->direct_cost,
            'total' => $this->item_direct_cost_total,
            'unit_cost_per_item' => $this->unit_cost_per_item,
            'percent' => $this->percent,
        ];
    }
}
