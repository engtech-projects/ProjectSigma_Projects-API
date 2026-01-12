<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TotalBilledBalanceToBeBilledResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'project_id' => $this->id,
            'project_code' => $this->code,
            'project_name_location' => $this->name . ' - ' . $this->location,
            'original_contract_amount' => $this->amount,
            'revised_contract_amount' => $this->revised_contract_amount,
            'revised_contract_percentage' => $this->revised_contract_percentage,
            'total_billed' => $this->total_billed,
            'total_billed_percentage' => $this->total_billed_percentage,
            'balance_to_be_billed' => $this->balance_to_be_billed,
            'balance_to_be_billed_percentage' => $this->balance_to_be_billed_percentage,
            'ntp_date' => $this->ntp_date,
        ];
    }
}
