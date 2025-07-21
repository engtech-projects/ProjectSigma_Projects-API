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
        ];
    }
}
