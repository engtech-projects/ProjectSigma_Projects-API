<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CumulativeBillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'year_month'       => $this["year_month"],
            'total_amount'     => $this["total_amount"],
            'cumulative_total' => $this["cumulative_total"]
        ];
    }
}
