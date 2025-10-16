<?php

namespace App\Http\Resources\Uom;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UomCollection extends JsonResource
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
            'name' => $this->name,
            'symbol' => $this->symbol,
            'description' => $this->description,
            'source_id' => $this->source_id,
        ];
    }
}
