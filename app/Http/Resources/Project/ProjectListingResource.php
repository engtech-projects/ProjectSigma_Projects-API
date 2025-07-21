<?php

namespace App\Http\Resources\Project;

use App\Enums\TssStage;
use App\Http\Resources\Approvals\ApprovalAttributeCollection;
use App\Http\Resources\BoqPart\BoqPartCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'amount' => $this->amount,
            'code' => $this->code,
            'contract_id' => $this->contract_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_formatted,
            'updated_at' => $this->updated_at_formatted,
            'stage' => $this->tss_stage === TssStage::PENDING->value
                ? $this->tss_stage
                : $this->marketing_stage,
            'status' => $this->status,
            'is_original' => $this->is_original,
            'version' => $this->version,
            'summary_of_rates' => $this->summary_of_rates,
            'summary_of_bid' => $this->summary_of_bid,
            'project_identifier' => $this->project_identifier,
            'implementing_office' => $this->implementing_office,
            'current_revision_id' => $this->current_revision_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_formatted,
            'updated_at' => $this->updated_at_formatted,
            'cash_flow' => $this->cash_flow ? $this->cash_flow : null,
            'approvals' => new ApprovalAttributeCollection(['approvals' => $this?->approvals]),
            'phases' => $this->whenLoaded('phases', fn () => BoqPartCollection::collection($this->phases)),
        ];
    }
}
