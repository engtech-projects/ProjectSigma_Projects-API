<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectStatusRequest;

class ProjectStatusController extends Controller
{
    public function updateStatus(UpdateProjectStatusRequest $request, Project $project)
    {
        $status = ProjectStatus::from($request->status); // Convert string to enum
        $project->updateStatus($status);

        return response()->json([
            'message' => 'Project status updated successfully.',
            'project' => $project,
        ]);
    }

    public function archive(Project $project)
    {
        $project->archive();
    }

    public function complete(Project $project)
    {
        $project->complete();
    }

}
