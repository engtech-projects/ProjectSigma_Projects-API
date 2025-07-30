<?php

namespace App\Http\Resources;

use App\Http\Resources\Project\ProjectDetailResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionResource extends JsonResource
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
            'project_id' => $this->project_id,
            'project_uuid' => $this->project_uuid,
            'data' => $this->data,
            'comments' => $this->comments,
            'status' => $this->status,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'project' => new ProjectDetailResource($this->whenLoaded('project')),
        ];
    }
}
