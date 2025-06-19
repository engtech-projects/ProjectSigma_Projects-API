<?php

namespace App\Services;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Models\Phase;
use App\Models\Project;
use App\Models\ResourceItem;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function create(array $attr)
    {
        return DB::transaction(function () use ($attr) {
            $attr['stage'] = ProjectStage::DRAFT->value;
            $attr['status'] = ProjectStatus::OPEN->value;
            $attr['amount'] = $attr['amount'] ?? 0;
            $attr['created_by'] = auth()->user()->id;
            $attr['cash_flow'] = json_encode(array_fill_keys(['wtax', 'q1', 'q2', 'q3', 'q4'], [
                'accomplishment' => 0,
                'cashflow' => 0,
                'culmutative_accomplishment' => 0,
                'culmutative_cashflow' => 0,
            ]));
            $data = Project::create($attr);

            return new JsonResponse([
                'message' => 'Project created successfully.',
                'data' => $data,
            ], 201);
        });
    }

    public function changeSummaryRates(array $attr)
    {
        return DB::transaction(function () use ($attr) {
            DB::table('resources')
                ->whereIn('id', $attr['ids'])
                ->update(['unit_cost' => $attr['unit_cost']]);

            return new JsonResponse([
                'message' => 'Summary rates updated successfully, Number of Direct Cost Affected: '.count($attr['ids']),
            ], 200);
        });
    }

    public function withPagination(array $attr)
    {
        $query = Project::query();

        $query->when(isset($attr['key']), function ($query) use ($attr) {
            $query->where('stage', $attr['key']);
        });
        $query->when(isset($attr['status']), function ($query) use ($attr) {
            if ($attr['status'] === ProjectStatus::DRAFT->value) {
                $query->where('created_by', auth()->user()->id);
            }
            if ($attr['status'] === ProjectStatus::MY_PROJECT->value) {
                $query->where('created_by', auth()->user()->id);
            } else {
                $query->where('stage', $attr['status']);
            }
        });
        $query->with('revisions', function ($query) {
            return $query->latestRevision();
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public function update(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            $project->fill($attr)->save();

            return new JsonResponse([
                'message' => 'Project updated successfully.',
                'data' => $project,
            ], 200);
        });
    }

    public function assignTeam(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            foreach ($attr as $personnel) {
                $personnel['project_id'] = $project->id;
                $project->team()->updateOrCreate(
                    ['id' => $personnel['id'] ?? null],
                    $personnel
                );
            }

            return new JsonResponse([
                'message' => 'Team assigned successfully.',
                'data' => $project->team()->get(),
            ], 200);
        });
    }

    public static function changeToDraft($id)
    {
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            $project->stage = ProjectStage::DRAFT->value;
            $project->status = ProjectStatus::DRAFT->value;
            $project->save();

            return true;
        });
    }

    public static function changeToAwarded($id)
    {
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            $project->stage = ProjectStage::AWARDED->value;
            $project->status = ProjectStatus::ONGOING->value;
            $project->save();

            return true;
        });
    }

    public static function changeToProposal($id)
    {
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            $project->stage = ProjectStage::PROPOSAL->value;
            $project->status = ProjectStatus::OPEN->value;
            $project->save();

            return true;
        });
    }
    public static function changeToBidding($id)
    {
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            $project->stage = ProjectStage::BIDDING->value;
            $project->save();

            return true;
        });
    }

    public static function changeToArchived($id)
    {
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id);
            $project->stage = ProjectStage::ARCHIVED->value;
            $project->status = ProjectStatus::ARCHIVED->value;
            $project->save();

            return true;
        });
    }
    public static function replicate($attribute)
    {
        $id = $attribute['id'];
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id)->load('phases.tasks.resources');
            $newProjectData = [
                'parent_project_id' => $id,
                'contract_id' => $project->contract_id.'-COPY',
                'code' => null,
                'name' => $project->name . '-COPY',
                'location' => $project->location,
                'nature_of_work' => $project->nature_of_work,
                'amount' => $project->amount,
                'contract_date' => $project->contract_date,
                'duration' => $project->duration,
                'noa_date' => $project->noa_date,
                'ntp_date' => $project->ntp_date,
                'license' => $project->license,
                'stage' => ProjectStage::DRAFT->value,
                'status' => ProjectStatus::DRAFT->value,
                'is_original' => 0,
                'version' => $project->version,
                'project_identifier' => $project->project_identifier,
                'implementing_office' => $project->implementing_office,
                'current_revision_id' => $project->current_revision_id,
                'cash_flow' => $project->cash_flow,
                'created_by' => auth()->user()->id,
            ];

            $newProject = Project::create($newProjectData);

            foreach ($project->phases as $phase) {
                $newPhaseData = [
                    'project_id' => $newProject->id,
                    'name' => $phase->name,
                    'description' => $phase->description,
                    'total_cost' => $phase->total_cost,
                ];
                $newPhase = Phase::create($newPhaseData);

                foreach ($phase->tasks as $task) {
                    $newTaskData = [
                        'phase_id' => $newPhase->id,
                        'name' => $task->name,
                        'description' => $task->description,
                        'quantity' => $task->quantity,
                        'unit' => $task->unit,
                        'unit_price' => $task->unit_price,
                        'amount' => $task->amount,
                    ];
                    $newTask = Task::create($newTaskData);

                    foreach ($task->resources as $resource) {
                        $newResourceData = [
                            'task_id' => $newTask->id,
                            'name_id' => $resource->name_id,
                            'description' => $resource->description,
                            'quantity' => $resource->quantity,
                            'unit' => $resource->unit,
                            'unit_cost' => $resource->unit_cost,
                            'unit_count' => $resource->unit_count,
                            'resource_count' => $resource->resource_count,
                            'total_cost' => $resource->total_cost,
                        ];
                        ResourceItem::create($newResourceData);
                    }
                }
            }

            return response()->json([
                'message' => 'Project replicated successfully.',
                'data' => $newProject->load('phases.tasks.resources'),
            ], 201);
        });
    }
}
