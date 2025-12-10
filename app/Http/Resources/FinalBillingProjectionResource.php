<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinalBillingProjectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year_month' => $this['year_month'] ?? null,
            'net_evenpar' => $this['net_evenpar'] ?? null,
            'monthly_net_gt' => $this['net_evenpar'] ?? 0,
            'projects' => ProjectsBillingSumarryResource::collection($this['projects']),
        ];
    }
}
