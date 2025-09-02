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
    public function scopeFilterByTaskId($query, $taskId)
    {
        return $query->where('task_id', 'like', "%{$taskId}%");
    }
    public function scopeFilterByResourceType($query, $resourceType)
    {
        return $query->where('resource_type', 'like', "%{$resourceType}%");
    }
    public function cascadeUnitCostToOtherResourceItemsWithSameProjectAndUnit(): void
    {
        if ($this->resource_type !== ResourceType::MATERIALS) {
            return;
        }
        $projectId = $this->task->part->project_id;
        self::whereHas('task.part', function ($query) use ($projectId) {
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
    }
}
