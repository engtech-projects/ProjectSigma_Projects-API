<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectRevisionsSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_id' => $this->id,
            'project_code' => $this->code,
            'project_name' => $this->name,
            'version' => $this->version,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'revisions' => RevisionResource::collection($this->revisions),
        ];
    }

}
