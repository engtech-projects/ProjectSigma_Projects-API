<?php

namespace App\Http\Resources\Project;

use App\Enums\TssStage;
use App\Http\Resources\Approvals\ApprovalAttributeCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_project_id' => $this->parent_project_id,
            'code' => $this->code,
            'name' => $this->name,
            'contract_id' => $this->contract_id,
            'location' => $this->location,
            'amount' => $this->amount,
            'contract_date' => $this->contract_date,
            'duration' => $this->duration,
            'license' => $this->license,
            'stage' => $this->tss_stage === TssStage::PENDING->value
                ? $this->tss_stage
                : $this->marketing_stage,
            'status' => $this->status,
            'is_original' => $this->is_original,
            'version' => $this->version,
            'summary_of_rates' => $this->summary_of_rates,
            'summary_of_bid' => $this->summary_of_bid,
            'current_revision_id' => $this->current_revision_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_formatted,
            'cash_flow' => $this->cash_flow ? $this->cash_flow : null,
            'approvals' => new ApprovalAttributeCollection(['approvals' => $this?->approvals]),
            'phases' => PhaseResource::collection($this->whenLoaded('phases')),
        ];
    }
}
