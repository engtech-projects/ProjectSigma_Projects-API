<?php

namespace App\Http\Resources;

use App\Models\ResourceItem;
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
            'unit_price' => number_format($this->unit_price, 2),
            'contact_cost' => [
                'quantity' => number_format($this->quantity, 3),
                'unit' => $this->unit,
                'amount' => number_format($this->amount, 2)
            ],
            'direct_cost' => $this->resources->map(function (ResourceItem $item) {
                return [
                    'resource_item_id' => $item->id,
                    'resource_type' => $item->resource_type,
                    'total_cost' => number_format($item->total_cost),
                ];
            }),
            'total' => number_format($this->resources->sum('total_cost'), 2),
            'unit_cost_per_item' => number_format($this->resources->sum('total_cost') / $this->quantity),
            'percent' => number_format(($this->quantity > 0 && $this->unit_price > 0) ? (($this->resources->sum('total_cost') / $this->quantity) / $this->unit_price) * 100 : 0, 2) . '%',
        ];
    }
}
