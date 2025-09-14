<?php

namespace App\Models;

use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResourceItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'resources';
    protected $fillable = [
        'task_id',
        'setup_item_profile_id',
        'resource_type',
        'description',
        'unit_count',
        'quantity',
        'unit',
        'unit_cost',
        'resource_count',
        'total_cost',
        'consumption_rate',
        'consumption_unit',
        'labor_cost_category',
        'work_time_category',
        'remarks',
        'status',
    ];
    protected $casts = [
        'resource_type' => ResourceType::class,
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    public function task(): BelongsTo
    {
        return $this->belongsTo(BoqItem::class, 'task_id', 'id');
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function setupItemProfile(): BelongsTo
    {
        return $this->belongsTo(SetupItemProfiles::class, 'setup_item_profile_id', 'id');
    }
    public function cashflows()
    {
        return $this->hasMany(Cashflow::class);
    }
    public function scopeFilterByTaskId($query, $taskId)
    {
        return $query->where('task_id', 'like', "%{$taskId}%");
    }
    public function scopeFilterByResourceType($query, $resourceType)
    {
        return $query->where('resource_type', 'like', "%{$resourceType}%");
    }
    public function syncUnitCostAcrossProjectResources(): int
    {
        if ($this->resource_type !== ResourceType::MATERIALS) {
            return 0;
        }
        $projectId = $this->task->phase->project_id;
        // Step 0: sync current resource totals
        $selfUpdated = 0;
        $computed = (float) $this->quantity * (float) $this->unit_cost;
        if ((float) $this->total_cost !== $computed) {
            $this->total_cost = $computed;
            $this->save();
            $selfUpdated = 1;
        }
        // ✅ Step 1: Update only matching resources (same project, unit, description)
        $affectedTaskIds = self::whereHas('task.phase', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->where('resource_type', $this->resource_type)
            ->where('unit', $this->unit)
            ->where('description', $this->description)
            ->where('id', '!=', $this->id)
            ->pluck('task_id')
            ->unique();
        $affectedResources = self::whereHas('task.phase', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->where('resource_type', $this->resource_type)
            ->where('unit', $this->unit)
            ->where('description', $this->description)
            ->where('id', '!=', $this->id)
            ->update([
                'unit_cost'  => $this->unit_cost,
                'total_cost' => DB::raw('quantity * ' . (float) $this->unit_cost),
            ]);
        // ✅ Step 2: Bulk recalc all affected task amounts (including current one)
        $taskTotals = self::select('task_id', DB::raw('SUM(total_cost) as total'))
            ->whereIn('task_id', $affectedTaskIds->push($this->task->id))
            ->whereIn('task_id', $affectedTaskIds->push($this->task->id)->unique())
            ->groupBy('task_id')
            ->pluck('total', 'task_id');
        foreach ($taskTotals as $taskId => $total) {
            $task = BoqItem::find($taskId); // BoqItem = tasks
            if ($task && $task->can_update_total_amount) {
                $task->update(['amount' => $total]);
            }
        }
        // ✅ Step 3: Update the project total once
        BoqItem::updateTotalProject($projectId);
        return (int) $affectedResources + $selfUpdated;
    }
}
