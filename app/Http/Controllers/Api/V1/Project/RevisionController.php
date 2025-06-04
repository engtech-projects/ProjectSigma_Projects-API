<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Revision\ApproveProposalRequest;
use App\Http\Requests\Revision\RejectProposalRequest;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Revision\RevisionCollection;
use App\Models\Project;
use App\Models\Revision;
use App\Services\ProjectService;
use DB;
use Illuminate\Http\Request;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::revised()->latest()->with(['revisions'])->paginate(config('services.pagination.limit'));

        return response()->json(new ProjectCollection($projects), 200);
    }

    public function addRevision($status, $id)
    {
        DB::transaction(function () use ($status, $id) {
            $project = Project::findOrFail($id)->load('phases.tasks.resources');
            $revisionCount = Revision::where('project_id', $project->id)->where('status', $status)->count();
            Revision::create([
                'project_id' => $project->id,
                'project_uuid' => $project->uuid,
                'data' => $project->toJson(),
                'version' => $revisionCount + 1.0,
                'status' => $status,
            ]);
        });

        return true;
    }

    public function show(Revision $revision)
    {
        return response()->json(new RevisionCollection($revision), 200);
    }

    public function changeToProposal(ApproveProposalRequest $request)
    {
        $validatedData = $request->validated();
        $this->addRevision(ProjectStage::PROPOSAL->value, $validatedData['id']);
        DB::transaction(function () use ($validatedData) {
            ProjectService::changeToProposal($validatedData['id']);
            $revision = Project::findOrFail($validatedData['id']);
            $revision->status = ProjectStage::PROPOSAL->value;
            $revision->save();
        });

        return response()->json([
            'message' => 'Project approved to proposal',
        ], 200);
    }
    public function changeToBidding(ApproveProposalRequest $request)
    {
        $validatedData = $request->validated();
        $this->addRevision(ProjectStage::BIDDING->value, $validatedData['id']);
        DB::transaction(function () use ($validatedData) {
            ProjectService::changeToBidding($validatedData['id']);
            $revision = Project::findOrFail($validatedData['id']);
            $revision->status = ProjectStage::BIDDING->value;
            $revision->save();
        });

        return response()->json([
            'message' => 'Project approved to bidding',
        ], 200);
    }

    public function returnToDraft(RejectProposalRequest $request)
    {
        $validatedData = $request->validated();
        $this->addRevision(ProjectStage::DRAFT->value, $validatedData['id']);
        DB::transaction(function () use ($validatedData) {
            ProjectService::changeToDraft($validatedData['id']);
            $revision = Project::findOrFail($validatedData['id']);
            $revision->status = ProjectStage::DRAFT->value;
            $revision->save();
        });

        return response()->json([
            'message' => 'Project returned to draft',
        ], 200);
    }

    public function archive(Request $request, Revision $revision)
    {
        $validatedData = $request->validated();
        $this->addRevision(ProjectStage::ARCHIVED->value, $validatedData['id']);
        DB::transaction(function () use ($validatedData) {
            ProjectService::changeToArchived($validatedData['id']);
            $revision = Project::findOrFail($validatedData['id']);
            $revision->status = ProjectStage::ARCHIVED->value;
            $revision->save();
        });

        return response()->json([
            'message' => 'Project archived',
        ], 200);
    }
}
