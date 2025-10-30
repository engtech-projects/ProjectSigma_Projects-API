<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectChangeRequestResource extends JsonResource
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
            'resource_type' => $this->resource_type,
            'project_id' => $this->project_id,
            'request_type' => $this->request_type,
            'changes' => $this->changes,
            'request_status' => $this->request_status,
            'created_by' => $this->created_by,
            'approvals' => $this->approvals,
        ];
    }
}
