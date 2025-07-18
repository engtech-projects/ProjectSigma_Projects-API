<?php

namespace App\Http\Resources\Project;

use App\Enums\TssStage;
use App\Http\Resources\Approvals\ApprovalAttributeCollection;
use App\Http\Resources\Phase\BOQPartCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCollection extends JsonResource
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
            'parent_project_id' => $this->parent_project_id,
            'contract_id' => $this->contract_id,
            'code' => $this->code,
            'name' => $this->name,
            'location' => $this->location,
            'nature_of_work' => $this->nature_of_work,
            'amount' => $this->amount,
            'contract_date' => $this->contract_date,
            'duration' => $this->duration,
            'noa_date' => $this->noa_date,
            'ntp_date' => $this->ntp_date,
            'license' => $this->license,
            'stage' => $this->tss_stage === TssStage::PENDING->value
                ? $this->marketing_stage
                : $this->tss_stage,
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
            'phases' => $this->whenLoaded('phases', fn () => BOQPartCollection::collection($this->phases)),
        ];
    }
}
