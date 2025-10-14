<?php

namespace App\Http\Resources;

use App\Http\Resources\Project\BoqPartResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TssProjectDetailResource extends JsonResource
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
            'ntp_date' => $this->ntp_date?->format('Y-m-d'),
            'noa_date' => $this->noa_date?->format('Y-m-d'),
            'duration' => $this->duration,
            'license' => $this->license,
            'stage' => $this->marketing_stage,
            'status' => $this->status,
            'is_original' => $this->is_original,
            'version' => $this->version,
            'summary_of_rates' => $this->summary_of_rates,
            'summary_of_bid' => $this->summary_of_bid,
            'current_revision_id' => $this->current_revision_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_formatted,
            'updated_at' => $this->updated_at_formatted,
            'cash_flow' => $this->cash_flow ? $this->cash_flow : null,
            'tss_cashflow' => ProjectTssCashflowResource::collection($this->whenLoaded('cashflows')),
            'phases' => BoqPartResource::collection($this->whenLoaded('phases')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'total_cost' => $this->total_task_amount,
            'draft_total_cost' => $this->total_draft_task_amount,
            'formatted_total_cost' => $this->formatted_total_cost,
            'abc' => $this->abc,
            'bid_date' => $this->bid_date?->format('Y-m-d'),
        ];
    }
}
