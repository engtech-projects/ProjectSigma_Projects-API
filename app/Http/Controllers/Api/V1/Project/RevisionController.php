<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Models\Revision;
use App\Enums\RevisionStatus;
use App\Http\Resources\Revision\RevisionResource;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        // $projects = Project::revised()->latest()->with(['revisions'])->paginate(10);
		// return response()->json(new ProjectCollection($projects), 200);
    }

    public function revise(Request $request, Project $project)
    {        
        # project should be a replica and open status
        if( !$project->isOriginal() && $project->isOpen() )
        {
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
        return response()->json(new RevisionResource($revision), 200);
    }

    public function approve(Request $request, Revision $revision)
    {
        // if previous status is active - set
        // if( $revision->status == RevisionStatus::DRAFT->label() ) {
        //     return response()->json(['message' => 'draft'], 200);
        // }else{
        //     return response()->json(['message' => $revision->status], 200);
        // }

        // $revision->status = RevisionStatus::ACTIVE;
        // $revision->comments = $request->comments;
        // $revision->save();

        return response()->json($revision, 200);
    }

    public function reject(Request $request, Revision $revision)
    {
        $revision->status = RevisionStatus::REJECTED;
        $revision->comments = $request->comments;
        $revision->save();

        // if( $revision->status == RevisionStatus::DRAFT->label() ) {
        //     return response()->json(['message' => 'draft'], 200);
        // }else{
        //     return response()->json(['message' => $revision->status], 200);
        // }
     
    }

    public function archive(Request $request, Revision $revision)
    {
        return response()->json($revision, 200);
    }

}
