<?php
namespace App\Http\Resources\Project;
use Illuminate\Http\Resources\Json\JsonResource;
class ProjectLiveListingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'location'    => $this->location,
            'amount'      => number_format((float) $this->amount, 2, '.', ','), // 👈 formatted
            'code'        => $this->code,
            'contract_id' => $this->contract_id,
            'created_at'  => $this->created_at_formatted,
            'status'      => $this->status,
        ];
    }
}
