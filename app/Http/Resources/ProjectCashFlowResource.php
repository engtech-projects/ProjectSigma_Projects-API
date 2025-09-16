<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCashflowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->date,
            'percent' => $this->percent,
            'total_amount' => $this->total_amount,
            'items' => CashflowItemResource::collection($this->whenLoaded('cashflowItems'))
        ];
    }
}
