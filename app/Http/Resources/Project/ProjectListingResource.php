<?php
namespace App\Http\Resources\Project;
use App\Enums\ProjectStatus;
use App\Enums\TssStage;
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
            'formatted_amount' => number_format($this->amount, 2),
            'code' => $this->code,
            'contract_id' => $this->contract_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_formatted,
            'updated_at' => $this->updated_at_formatted,
            'stage' => $this->tss_stage === TssStage::PENDING->value
                ? $this->tss_stage
                : $this->status,
            'tss_submission' => $this->status === ProjectStatus::ONGOING->value ? '✅' : '❌',
            'status' => $this->status,
        ];
    }
}
