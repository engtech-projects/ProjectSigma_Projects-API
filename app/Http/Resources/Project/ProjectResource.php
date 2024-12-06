<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phase\PhaseResource;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Http\Resources\Revision\RevisionResource;

class ProjectResource extends JsonResource
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
            'project_identifier' => $this->project_identifier,
            'current_revision_id' => $this->current_revision_id,
			'contract_id' => $this->contract_id,
			'code' => $this->code,
			'name' => $this->name,
			'location' => $this->location,
			'amount' => $this->amount,
			'contract_date' => $this->contract_date,
			'duration' => $this->duration,
			'noa_date' => $this->noa_date,
			'ntp_date' => $this->ntp_date,
			'license' => $this->license,
			'status' => $this->status,
			'nature_of_work' => $this->nature_of_work,
            'implementing_office' => $this->implementing_office,
			'stage' => $this->stage,
			'is_original' => $this->is_original,
			'version' => $this->version,
            'attachments' => AttachmentResource::make($this->whenLoaded('attachments')),
			'phases' => PhaseResource::make($this->whenLoaded('phases')),
            'revisions' => RevisionResource::make($this->whenLoaded('revisions')),
		];
    }
}


// {
// 	"contract_id" : "",
// 	"version" : "1.0",
// 	"is_original" : true,
// 	"phases" : [
// 		{
// 			"phase_1" : {
// 				"name" : "",
// 				"description" : "",
// 				"tasks" : [
// 					{
// 						"name" : "",
// 						"description" : "",
// 						"resources" : [{}],
// 						"direct_cost" : {},
// 					},
// 				]
// 			},
// 		},
// 	],
// 	"assignment" : [{}],
// 	"timeline" : [{}],
// 	"documents" : {},
// }