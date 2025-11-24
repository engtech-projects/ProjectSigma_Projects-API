<?php

namespace App\Models;

use App\Enums\TimelineClassification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'timeline_classification',
        'item_id',
        'name',
        'start_date',
        'end_date',
        'duration_days',
        'weight_percent',
        'status',
    ];
    protected $casts = [
        'timeline_classification' => TimelineClassification::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'weight_percent' => 'decimal:2',
    ];
    public function task(): BelongsTo
    {
        return $this->belongsTo(BoqItem::class);
    }
    public function scopeSortByOrder($query, $data)
    {
        return $query->orderBy('sort_order', 'asc');
    }
    public function taskScheduleWeek(): BelongsTo
    {
        return $this->belongsTo(TaskScheduleWeek::class);
    }
    public function resources()
    {
        return $this->hasMany(ResourceItem::class, 'task_id', 'id');
    }
    public function getWeightedResourcesAttribute()
    {
        return $this->resources()
            ->selectRaw('id, resource_type, SUM(total_cost) as total_cost')
            ->groupBy('id', 'resource_type')
            ->get()
            ->map(function ($row) {
                return [
                    'resource_id' => $row->id,
                    'resource_type' => $row->resource_type,
                    'total_cost' => (float) $row->total_cost,
                    'weighted_total_cost' => (float) ($row->total_cost * $this->weight_percent),
                ];
            });
    }
}
