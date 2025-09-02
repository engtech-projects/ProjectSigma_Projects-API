<?php
namespace App\Services;
use App\Models\Project;
use App\Models\ResourceItem;
use App\Models\BoqItem;
use Illuminate\Support\Facades\DB;
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
            if (isset($request['unit_count'])) {
                $request['total_cost'] = ($request['quantity'] * $request['unit_cost']) * $request['unit_count'];
            } else {
                $request['total_cost'] = $request['quantity'] * $request['unit_cost'];
            }
            $data = ResourceItem::create($request);
            $data->cascadeUnitCostToOtherResourceItemsWithSameProjectAndUnit();
            $task = BoqItem::findOrFail($request['task_id'])->load(['resources', 'phase']);
            if ($task->can_update_total_amount) {
                $task->update([
                    'amount' => $task->resources->sum('total_cost'),
                ]);
                self::updateTotalProject($task->phase->project_id);
            }
            return $data;
        });
    }
    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = ResourceItem::findOrFail($id);
            if (isset($request['unit_count'])) {
                $request['total_cost'] = ($request['quantity'] * $request['unit_cost']) * $request['unit_count'];
            } else {
                $request['total_cost'] = $request['quantity'] * $request['unit_cost'];
            }
            $data->fill($request)->save();
            $task = BoqItem::findOrFail($request['task_id'])->load(['resources', 'phase']);
            if ($task->can_update_total_amount) {
                $task->update([
                    'amount' => $task->resources->sum('total_cost'),
                ]);
                self::updateTotalProject($task->phase->project_id);
            }
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
}
