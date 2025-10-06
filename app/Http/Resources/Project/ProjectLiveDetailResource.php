<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectLiveDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_project_id' => $this->parent_project_id,
            'project_identifier' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'contract_id' => $this->contract_id,
            'location' => $this->location,
            'position' => $this->position,
            'designator' => $this->designator,
            'implementing_office' => $this->implementing_office,
            'amount' => $this->amount,
            'nature_of_work' => $this->nature_of_work,
            'contract_date' => $this->contract_date?->format('Y-m-d'),
            'duration' => $this->duration,
            'license' => $this->license,
            'summary_of_rates' => $this->summary_of_rates,
            'summary_of_bid' => $this->summary_of_bid,
            'created_at' => $this->created_at_formatted,
            'total_cost' => $this->phases->flatMap->tasks->sum('amount'),
            'abc' => $this->abc,
            'bid_date' => $this->bid_date?->format('Y-m-d'),
        ];
    }
}
