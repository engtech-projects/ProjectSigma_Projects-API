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
        'duration_days',
        'weight_percent',
        'status',
    ];

    protected $casts = [
        'timeline_classification' => TimelineClassification::class,
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
}
