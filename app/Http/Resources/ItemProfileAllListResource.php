<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemProfileAllListResource extends JsonResource
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
            'item_code' => $this->item_code,
            'item_description' => $this->item_description,
            'unit_name' => $this->uom_name,
            'unit' => $this->uom_symbol,
            'uom_conversion_value' => $this->uom_conversion_value,
            'item_group' => $this->item_group,
            'sub_item_group' => $this->sub_item_group,
            'inventory_type' => $this->inventory_type,
            'active_status' => $this->active_status,
            'is_approved' => $this->is_approved,
        ];
    }
}
