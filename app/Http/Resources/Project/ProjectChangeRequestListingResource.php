<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectChangeRequestListingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->project->id,
            'name'        => $this->project->name,
            'location'    => $this->project->location,
            'amount'      => (float) $this->project->amount,
            'amount_formatted' => number_format((float) $this->project->amount, 2, '.', ','),
            'code'        => $this->project->code,
            'contract_id' => $this->project->contract_id,
            'created_at'  => $this->project->created_at_formatted,
            'status'      => $this->status,
        ];
    }
}
