<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashflowItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->item->id,
            'code' => $this->item->setupItemProfile->item_code ?? 'N/A',
            'name' => $this->item->resource_type,
            'percent' => $this->percent,
            'amount' => $this->item->total_cost,
        ];
    }
}
