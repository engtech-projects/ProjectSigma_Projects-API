<?php

namespace App\Services;

use App\Http\Resources\Project\ProjectDetailResource;
use App\Http\Resources\TssProjectDetailResource;
use App\Models\Project;
use App\Models\Revision;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TssRevisionService
{
    public function createTssRevision(Project $project, User $user, $comments = null)
    {
        DB::transaction(function () use ($project, $user, $comments) {
            $version = Revision::nextVersion($project->id);
            $project->loadMissing(['phases.tasks.resources', 'boms']);
            Revision::create([
                'project_id' => $project->id,
                'project_uuid' => $project->uuid,
                'data' => json_encode(TssProjectDetailResource::make($project)->toArray(request())),
                'comments' => $comments,
                'version' => $version,
                'created_by' => $user->id,
            ]);
        });
    }
}
