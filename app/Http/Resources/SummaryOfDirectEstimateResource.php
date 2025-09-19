<?php

namespace App\Http\Resources;

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ResourceItem;

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
            'unit_price' => $this->getFormatted('unit_price', 2),
            'contract_cost' => [
                'quantity' => $this->getFormatted('quantity', 3),
                'unit' => $this->unit,
                'amount' => $this->getFormatted('amount', 2),
            ],
            'direct_cost' => $this->resources->map(function (ResourceItem $item) {
                return [
                    'resource_item_id' => $item->id,
                    'resource_type' => $item->resource_type,
                    'total_cost' => $item->getFormatted('total_cost', 2),
                ];
            }),
            'total' => $this->getFormattedValue($total, 2),
            'unit_cost_per_item' => $this->getFormattedValue($unitCostPerItem, 2),
            'percent' => $this->getFormattedValue($percent, 2) . '%',
        ];
    }
}
