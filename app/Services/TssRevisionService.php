<?php

namespace App\Services;

use App\Http\Requests\TssRevisionRequest;
use App\Http\Resources\ProjectTssRevisionResource;
use App\Models\Project;
use App\Models\Revision;
use Illuminate\Support\Facades\DB;

class TssRevisionService
{
    public function createTssRevision(Project $project, TssRevisionRequest $request)
    {
        DB::transaction(function () use ($project, $request) {
            $project->loadMissing([
                'phases.tasks.resources',
                'boms',
                'attachments'
            ]);
            Revision::create([
                'project_id' => $project->id,
                'project_uuid' => $project->uuid,
                'data' => json_encode(ProjectTssRevisionResource::make($project)->toArray($request)),
                'comments' => $request->input('comments'),
                'version' => Revision::nextVersion($project->id),
            ]);
        });
    }
}
