<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "task_schedule";

    protected $fillable = [
        'item_id',
        'name',
        'original_start',
        'original_end',
        'current_start',
        'current_end',
        'duration_days',
        'weight_percent',
        'status',
    ];

    protected $casts = [
        'original_start' => 'date',
        'original_end' => 'date',
        'current_start' => 'date',
        'current_end' => 'date',
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
