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
        $data = is_array($this->data) ? $this->data : json_decode($this->data, true) ?? [];
        $projectName = $data['name'] ?? null;
        $version = $this->version ?? null;
        return [
            'id' => $this->id,
            'project_id' => $data['id'] ?? null,
            'project_code' => $data['code'] ?? null,
            'project_name' => $projectName,
            'version' => $this->version,
            'project_name_version' => trim($projectName . ' v' . $version),
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
