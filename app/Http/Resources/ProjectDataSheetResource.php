<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDataSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_classification' => $this->project_classification,
            'project_limits' => $this->project_limits,
            'project_manager' => $this->project_manager,
            'project_engineer' => $this->project_engineer,
            'foreman_leadman' => $this->foreman_leadman,
            'materials_engineer' => $this->materials_engineer,
            'safety_officer' => $this->safety_officer,
            'project_management_specialist' => $this->project_management_specialist,
            'equipment_deployed' => $this->equipment_deployed,
            'suppliers' => $this->suppliers,
            'subcontractors' => $this->subcontractors,
        ];
    }
}
