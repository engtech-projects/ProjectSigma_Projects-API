<?php

namespace App\Services;

use App\Enums\ResourceType;
use App\Enums\TssStage;
use App\Models\Project;
use App\Models\ResourceItem;
use App\Models\BoqItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResourceService
{
    public static function withPagination($request)
    {
        $query = ResourceItem::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });
        $query->with('tasks');
        return $query->get();
    }
    public static function withProjects($request)
    {
        $query = ResourceItem::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });
        return $query->paginate(config('services.pagination.limit'));
    }
    public static function all()
    {
        return ResourceItem::all();
    }
    public static function create($request)
    {
        return DB::transaction(function () use ($request) {
            $data = ResourceItem::create($request);
            $data->syncUnitCostAcrossProjectResources();
            return $data;
        });
    }
    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = ResourceItem::findOrFail($id);
            $quantity  = (float) ($request['quantity'] ?? $data->quantity ?? 0);
            $unitCost  = (float) ($request['unit_cost'] ?? $data->unit_cost ?? 0);
            $unitCount = isset($request['unit_count'])
                ? (float) $request['unit_count']
                : ($data->unit_count ?? 1);
            $request['total_cost'] = round($quantity * $unitCost * $unitCount, 2);
            $data->fill($request)->save();
            $data->syncUnitCostAcrossProjectResources();
            return $data;
        });
    }
    public static function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $data = ResourceItem::findOrFail($id);
            $data->delete();
            $task = BoqItem::findOrFail($data->task_id)->load(['resources', 'phase']);
            if ($task->can_update_total_amount) {
                $task->update([
                    'amount' => $task->resources->sum('total_cost'),
                ]);
                self::updateTotalProject($task->phase->project_id);
            }
            return $data;
        });
    }
    public static function show($id)
    {
        return ResourceItem::findOrFail($id);
    }
    public static function updateTotalProject($project_id)
    {
        $project = Project::where('id', $project_id)->first();
        if ($project) {
            $totalAmount = BoqItem::whereHas('phase', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })->sum('amount');
            $project->update(['amount' => $totalAmount]);
        }
    }
    public function updateLabor13thMonth(ResourceItem $resource, float $percentage): ResourceItem
    {
        if ($resource->resource_type !== ResourceType::LABOR_EXPENSE) {
            throw ValidationException::withMessages([
                'resource_type' => 'Selected resource is not labor expense.',
            ]);
        }
        if ($resource->task->phase->project->tss_stage !== TssStage::DUPA_PREPARATION) {
            throw ValidationException::withMessages([
                'tss_stage' => 'Labor 13th month update allowed only during dupa preparation stage.',
            ]);
        }
        $resource->update(['percentage' => $percentage]);
        return $resource->fresh();
    }
}
