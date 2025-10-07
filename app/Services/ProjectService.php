<?php

namespace App\Services;

use App\Enums\MarketingStage;
use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Enums\TssStage;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectDetailResource;
use App\Models\BoqPart;
use App\Models\Project;
use App\Models\ResourceItem;
use App\Models\BoqItem;
use App\Models\CashflowItem;
use App\Models\Revision;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            $attr['marketing_stage'] = MarketingStage::DRAFT->value;
            $attr['tss_stage'] = TssStage::PENDING->value;
            $attr['status'] = ProjectStatus::PENDING->value;
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
        if (!isset($attr['ids']) || !is_array($attr['ids']) || empty($attr['ids'])) {
            return new JsonResponse(['message' => 'No IDs provided'], 422);
        }
        if (!isset($attr['unit_cost']) || !is_numeric($attr['unit_cost'])) {
            return new JsonResponse(['message' => 'Invalid unit cost'], 422);
        }
        return DB::transaction(function () use ($attr) {
            $resources = ResourceItem::whereIn('id', $attr['ids'])->get();
            $affected = 0;
            foreach ($resources as $resource) {
                $resource->unit_cost = $attr['unit_cost'];
                $resource->save();
                // Call your cascade function
                $resource->syncUnitCostAcrossProjectResources();
                $affected++;
            }
            return new JsonResponse([
                'message' => "Summary rates updated successfully, Number of Direct Cost Affected: {$affected}",
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
            if ($attr['status'] === ProjectStatus::PENDING->value) {
                $query->where('created_by', auth()->user()->id);
            } else {
                $query->where('stage', $attr['status']);
            }
        });
        $query->with('revisions', function ($query) {
            return $query->latestRevision();
        });
        return ProjectCollection::collection($query->paginate(config('services.pagination.limit')))->response()->getData(true);
    }
    public function update($project, array $payload)
    {
        return DB::transaction(function () use ($project, $payload) {
            $payload = Arr::only($payload, $project->getFillable());
            $project->update($payload);
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
            $project->marketing_stage = ProjectStage::DRAFT->value;
            $project->tss_stage = TssStage::PENDING->value;
            $project->status = ProjectStatus::PENDING->value;
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
            $project->marketing_stage = ProjectStage::PROPOSAL->value;
            $project->tss_stage = TssStage::PENDING->value;
            $project->status = ProjectStatus::PENDING->value;
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
            $project->status = ProjectStatus::COMPLETED->value;
            $project->save();
            return true;
        });
    }
    public static function replicate($attribute)
    {
        $id = $attribute['id'];
        return DB::transaction(function () use ($id) {
            $project = Project::findOrFail($id)->load('phases.tasks.resources');
            $maxVersion = Project::where('parent_project_id', $id)->max('version');
            $newProjectData = [
                'parent_project_id' => $id,
                'contract_id' => $project->contract_id . '-COPY',
                'code' => null,
                'name' => $project->name,
                'location' => $project->location,
                'nature_of_work' => $project->nature_of_work,
                'amount' => $project->amount,
                'contract_date' => $project->contract_date,
                'duration' => $project->duration,
                'noa_date' => $project->noa_date,
                'ntp_date' => $project->ntp_date,
                'license' => $project->license,
                'marketing_stage' => MarketingStage::DRAFT->value,
                'tss_stage' => TssStage::PENDING->value,
                'status' => ProjectStatus::PENDING->value,
                'is_original' => 0,
                'version' => $maxVersion + 1,
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
                $newPhase = BoqPart::create($newPhaseData);
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
                    $newTask = BoqItem::create($newTaskData);
                    foreach ($task->resources as $resource) {
                        $newResourceData = [
                            'task_id' => $newTask->id,
                            'resource_type' => $resource->resource_type,
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
                'message' => 'Project replicated as version ' . ($maxVersion + 1) . '.',
                'data' => $newProject->load('phases.tasks.resources'),
            ], 201);
        });
    }
    public function updateStage(ProjectStage $newStage)
    {
        $isTssUpdate = $this->project->marketing_stage->value === MarketingStage::AWARDED->value
            && in_array($newStage->value, array_map(fn ($stage) => $stage->value, TssStage::cases()), true);
        if ($isTssUpdate && $this->project->marketing_stage->value === MarketingStage::AWARDED->value && $this->project->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => 'Project must be approved to update TSS stage after marketing is awarded.',
            ]);
        }
        if (!$isTssUpdate) {
            $flow = array_map(fn ($stage) => $stage->value, MarketingStage::flow());
            $current = $this->project->marketing_stage->value;
        } else {
            $flow = array_map(fn ($stage) => $stage->value, TssStage::flow());
            $current = $this->project->tss_stage->value;
        }
        $currentIndex = array_search($current, $flow);
        $newIndex = array_search($newStage->value, $flow);
        if ($newIndex === false || $currentIndex === false || $newIndex !== $currentIndex + 1) {
            throw ValidationException::withMessages([
                'stage' => 'Invalid stage transition.',
            ]);
        }
        if (!$isTssUpdate) {
            $this->project->marketing_stage = $newStage->value;
            if ($newStage->value === MarketingStage::AWARDED->value) {
                $this->project->tss_stage = TssStage::DUPA_PREPARATION->value;
                $this->project->status = ProjectStatus::ONGOING->value;
                $this->createProjectRevision($this->project->status);
            }
        } else {
            $this->project->tss_stage = $newStage->value;
        }
        $this->project->save();
    }
    public function createProjectRevision($status)
    {
        $this->project->loadMissing(['phases.tasks.resources', 'attachments']);
        Revision::create([
            'project_id'   => $this->project->id,
            'project_uuid' => $this->project->uuid,
            'data'         => json_encode(ProjectDetailResource::make($this->project)->toArray(request())),
            'comments'     => null,
            'status'       => $status,
            'version'      => $this->project->version,
        ]);
    }
    public function revertToRevision(Revision $revision)
    {
        if ($revision->project_id != $this->project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Revision does not belong to this project',
            ], 400);
        }
        $projectData = json_decode($revision->data, true);
        if ($revision->status === ProjectStatus::PENDING->value) {
            $projectData['status'] = ProjectStatus::PENDING->value;
        }
        if ($revision->status === ProjectStatus::COMPLETED->value) {
            $projectData['status'] = ProjectStatus::COMPLETED->value;
        }
        try {
            DB::beginTransaction();
            $this->project->update($projectData);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Project reverted to revision',
                'data' => new ProjectDetailResource($this->project->fresh()),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to revert project to revision',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function storeCashflow(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $cashflow = $this->project->cashflows()->create([
                'date'         => $validated['date'],
                'total_amount' => 0,
            ]);
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $itemModel = ResourceItem::findorFail($item['item_id']);
                $amount = $itemModel->amount ?? $itemModel->total_cost ?? 0;
                $totalAmount += $amount;
                CashflowItem::updateOrCreate(
                    [
                        'cashflow_id' => $cashflow->id,
                        'item_id'     => $item['item_id'],
                        'percent'     => $item['percent'],
                    ],
                    [
                        'amount' => $amount,
                    ]
                );
            }
            $cashflow->update(['total_amount' => $totalAmount]);
            return $cashflow->load('cashflowItems.item');
        });
    }
    public function getTasksWithResources()
    {
        return $this->project->phases()
            ->with('tasks.resources')
            ->get()
            ->pluck('tasks')
            ->flatten();
    }
    public function calculateDirectCostDistribution($tasks)
    {
        $resources = $tasks->flatMap->resources;
        $resources = $resources->map(function ($item) {
            $totalCost = ($item->resource_type->value === "materials" && $item->setup_item_profile_id === null)
                ? 0
                : (float) $item->total_cost;
            return [
                'resource_type' => strtolower($item->resource_type->value),
                'total_cost'    => $totalCost,
            ];
        });
        $distribution = $resources->groupBy('resource_type')->map(function ($group) {
            return $group->sum('total_cost');
        });
        $directCostTotal = $distribution->sum();
        $grandTotal = (float) $tasks->sum('amount');
        $orderedTypes = ['materials', 'labor_expense', 'equipment_rental', 'fuel_oil_cost', 'overhead_cost'];
        $result = [];
        foreach ($orderedTypes as $label) {
            if (isset($distribution[$label])) {
                $total = (float) $distribution[$label];
                $percent = $grandTotal ? ($total / $grandTotal) * 100 : 0;
                $result[$label] = [
                    'total (PHP)' => number_format($total, 2),
                    'percent'     => number_format($percent, 2) . '%',
                ];
            }
        }
        $directPercent = $grandTotal ? ($directCostTotal / $grandTotal) * 100 : 0;
        $result['Direct Cost Total'] = [
            'total (PHP)' => number_format($directCostTotal, 2),
            'percent'     => number_format($directPercent, 2) . '%',
        ];
        return $result;
    }
}
