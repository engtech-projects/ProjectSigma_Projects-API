<?php

namespace App\Http\Resources\ProjectAssignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectAssignmentCollection extends ResourceCollection
{
    public static $wrap = 'project_assignment';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($projectAssignment) {
            return new ProjectAssignmentResource($projectAssignment);
        })->toArray();
    }
}
