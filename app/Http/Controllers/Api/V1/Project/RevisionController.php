<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\MarketingStage;
use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Enums\TssStage;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProjectRequest;
use App\Http\Requests\Revision\ApproveProposalRequest;
use App\Http\Requests\Revision\RejectProposalRequest;
use App\Http\Requests\TssRevisionRequest;
use App\Http\Resources\ProjectRevisionsSummaryResource;
use App\Http\Resources\RevisionResource;
use App\Models\Project;
use App\Models\Revision;
use App\Services\ProjectService;
use App\Services\TssRevisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisionController extends Controller
{
    public function index(FilterProjectRequest $request)
    {
        $validated = $request->validated();
        $projectKey = $validated['project_key'] ?? null;
        $listOfRevisions = Revision::when($projectKey, fn ($query) => $query->projectKey($projectKey))
            ->latest()
            ->paginate(config('services.pagination.limit'));
        return ProjectRevisionsSummaryResource::collection($listOfRevisions)
            ->additional([
                'success' => true,
                'message' => 'Revisions retrieved successfully',
            ]);
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
    public function copyAwardedProjectAsDraft(Revision $revision)
    {
        $projectData = json_decode($revision->data, true);
        try {
            DB::beginTransaction();
            if (!isset($projectData['name'], $projectData['location'])) {
                throw new \Exception('Missing required project data fields.');
            }
            // 1. Create or update project
            $project = Project::create([
                'parent_project_id' => $projectData['id'],
                'contract_id' => $projectData['contract_id'] ?? null,
                'code' => null,
                'name' => $projectData['name'],
                'location' => $projectData['location'],
                'amount' => $projectData['amount'],
                'duration' => $projectData['duration'],
                'nature_of_work' => $projectData['nature_of_work'],
                'contract_date' => $projectData['contract_date'],
                'ntp_date'  => null,
                'noa_date'  => null,
                'license' => $projectData['license'] ?? null,
                'is_original' => false,
                'version' => 1.0,
                'status' => ProjectStatus::PENDING->value,
                'marketing_stage' => MarketingStage::DRAFT->value,
                'tss_stage' => TssStage::PENDING->value,
                'project_identifier' => $projectData['project_identifier'],
                'implementing_office' => $projectData['implementing_office'] ?? null,
                'current_revision_id' => $projectData['current_revision_id'],
                'cash_flow' => $projectData['cash_flow'] ?? null,
                'designator' => $projectData['designator'] ?? null,
                'position' => $projectData['position'] ?? null,
                'created_by' => auth()->user()->id,
            ]);
            // 2. Restore related data
            if (!empty($projectData['phases'])) {
                foreach ($projectData['phases'] as $itemData) {
                    $phase = $project->phases()->create($itemData);
                    foreach ($itemData['tasks'] ?? [] as $taskData) {
                        $phase->tasks()->create($taskData);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Project duplicate sucessfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show(Revision $revision)
    {
        return response()->json([
            'success' => true,
            'message' => 'Revision retrieved successfully',
            'data' => new RevisionResource($revision),
        ], 200);
    }
    public function showProjectRevisions($project)
    {
        $revisions = Revision::where('project_id', $project->id)
            ->latest()
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Revisions retrieved successfully',
            'data' => ProjectRevisionsSummaryResource::collection($revisions),
        ], 200);
    }
    public function changeToProposal(ApproveProposalRequest $request)
    {
        $validatedData = $request->validated();
        $this->addRevision(ProjectStage::PROPOSAL->value, $validatedData['id']);
        DB::transaction(function () use ($validatedData) {
            ProjectService::changeToProposal($validatedData['id']);
            $revision = Project::findOrFail($validatedData['id']);
            $revision->status = ProjectStatus::PENDING->value;
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
            $revision->status = ProjectStatus::PENDING->value;
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
    public function revertToRevision(Project $project, Revision $revision)
    {
        $projectService = new ProjectService($project);
        $result = $projectService->revertToRevision($revision);
        return $result;
    }
    public function createTssRevision(Project $project, TssRevisionRequest $request)
    {
        try {
            $tssRevisionService = new TssRevisionService();
            $tssRevisionService->createTssRevision($project, $request);
            return response()->json([
                'success' => true,
                'message' => 'Project Tss revision created successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create TSS revision',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
