<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\RevisionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Revision\RevisionCollection;
use App\Models\Project;
use App\Models\Revision;
use Illuminate\Http\Request;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::revised()->latest()->with(['revisions'])->paginate(config('services.pagination.limit'));

        return response()->json(new ProjectCollection($projects), 200);
    }

    public function revise(Request $request, Project $project)
    {
        // project should be a replica and open status
        if (! $project->isOriginal() && $project->isOpen()) {
            $revision = Revision::create([
                'project_id' => $project->id,
                'project_uuid' => $project->uuid,
                'data' => $project->toJson(),
                'comments' => $request->comments,
                'status' => RevisionStatus::DRAFT,
            ]);

            return response()->json($revision, 200);
        }

    }

    public function show(Revision $revision)
    {
        return response()->json(new RevisionCollection($revision), 200);
    }

    public function approve(Request $request, Revision $revision)
    {
        return response()->json($revision, 200);
    }

    public function reject(Request $request, Revision $revision)
    {
        $revision->status = RevisionStatus::REJECTED;
        $revision->comments = $request->comments;
        $revision->save();
    }

    public function archive(Request $request, Revision $revision)
    {
        return response()->json($revision, 200);
    }
}
