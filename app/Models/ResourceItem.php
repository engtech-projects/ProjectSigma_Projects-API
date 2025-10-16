<?php

namespace App\Models;

use App\Enums\ResourceType;
use App\Traits\FormatNumbers;
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
    use FormatNumbers;
    protected $table = 'resources';
    protected $fillable = [
        'task_id',
        'setup_item_profile_id',
        'resource_type',
        'description',
        'unit_count',
        'quantity',
        'unit',
        'unit_name',
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
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
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
    public function getFormattedQuantityAttribute()
    {
        return $this->formatted($this->quantity);
    }
    public function getFormattedUnitCountAttribute()
    {
        return $this->formatted($this->unit_count);
    }
    public function getFormattedUnitCostAttribute()
    {
        return $this->formatted($this->unit_cost);
    }
    public function getFormattedTotalCostAttribute()
    {
        return $this->formatted($this->total_cost);
    }
    public function syncUnitCostAcrossProjectResources(): int
    {
        return DB::transaction(function () {
            $projectId = $this->task->phase->project_id;
            // Step 0: Always recalc current resource total
            $selfUpdated = $this->recalculateSelfTotal();
            // If NOT materials → only recalc totals
            if ($this->resource_type !== ResourceType::MATERIALS) {
                $this->updateTaskAndProjectTotals($this->task->id, $projectId);
                return $selfUpdated;
            }
            // ✅ Step 1: Update matching resources (same project, unit, description)
            $affectedTaskIds = $this->matchingResources()
                ->pluck('task_id')
                ->unique();
            $affectedResources = $this->matchingResources()
                ->update([
                    'unit_cost'  => $this->unit_cost,
                    'total_cost' => DB::raw('quantity * ' . (float) $this->unit_cost),
                ]);
            // ✅ Step 2: Recalc all affected task amounts (including current one)
            $allTaskIds = $affectedTaskIds->push($this->task->id)->unique();
            $taskTotals = self::select('task_id', DB::raw('SUM(total_cost) as total'))
                ->whereIn('task_id', $allTaskIds)
                ->groupBy('task_id')
                ->pluck('total', 'task_id');
            foreach ($taskTotals as $taskId => $total) {
                $task = BoqItem::find($taskId);
                if ($task && $task->can_update_total_amount) {
                    $task->update([
                        'amount'     => $total,
                        'unit_price' => $task->unit_cost_per,
                        'draft_amount'     => $total,
                        'draft_unit_price' => $task->unit_cost_per,
                    ]);
                }
            }
            // ✅ Step 3: Update project total once
            BoqItem::updateTotalProject($projectId);
            return $affectedResources + $selfUpdated;
        });
    }
    /**
     * Recalculate current resource total if needed.
     */
    private function recalculateSelfTotal(): int
    {
        $computed = (float) $this->quantity * (float) $this->unit_cost;
        if ((float) $this->total_cost !== $computed) {
            $this->total_cost = $computed;
            $this->save();
            return 1;
        }
        return 0;
    }
    /**
     * Scope-like helper for finding matching resources.
     */
    public function matchingResources()
    {
        $projectId = $this->task->phase->project_id;
        return self::whereHas('task.phase', fn ($query) =>
        $query->where('project_id', $projectId))
            ->where('resource_type', $this->resource_type)
            ->where('unit', $this->unit)
            ->where('description', $this->description)
            ->where('id', '!=', $this->id);
    }
    /**
     * Update task and project totals (for non-materials).
     */
    private function updateTaskAndProjectTotals(int $taskId, int $projectId): void
    {
        $taskTotal = self::where('task_id', $taskId)->sum('total_cost');
        $task = BoqItem::find($taskId);
        if ($task && $task->can_update_total_amount) {
            $task->update([
                'amount'     => $taskTotal,
                'unit_price' => $task->unit_cost_per,
                'draft_amount'     => $taskTotal,
                'draft_unit_price' => $task->unit_cost_per,
            ]);
        }
        BoqItem::updateTotalProject($projectId);
    }
}
