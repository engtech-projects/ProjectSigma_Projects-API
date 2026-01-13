<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstimatedNetIncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_name' => $this->name,
            'unit' => $this->unit,
            'contract_agency_data' => [
                'unit_cost' => $this->unit_price,
                'original' => [
                    'quantity' => $this->quantity,
                    'amount' => $this->amount,
                ],
                'proposed_revised_final' => [
                    'quantity' => $this->revised_quantity,
                    'amount' => $this->revised_amount,
                ]
            ],
            'implementation_data' => [
                'unit_cost' => $this->direct_cost_unit_price,
                'survey_actual_quantity' => $this->direct_cost_quantity,
                'direct_cost_amount' => $this->direct_cost_amount,
            ],
            'savings' => [
                'quantity' => $this->savings_quantity,
                'amount' => $this->savings_amount,
            ]
        ];
    }
}
