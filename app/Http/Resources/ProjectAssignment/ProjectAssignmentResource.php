<?php

namespace App\Http\Resources\ProjectAssignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectAssignmentResource extends JsonResource
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
            'uuid' => $this->uuid,
            'employee_id' => $this->employee_id,
            'name' => $this->employee->getFormattedFullname('last_first'),
            'project_id' => $this->project_id,
            'project_code' => $this->project->code,
            'project_status' => $this->project_status,
            'role' => null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
