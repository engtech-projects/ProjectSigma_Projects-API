<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskScheduleWeek extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Table name (optional if Laravel naming convention is followed)
    protected $table = 'task_schedule_weeks';
    // Mass assignable attributes
    protected $fillable = [
        'task_schedule_id',
        'week_start_date',
        'week_end_date',
    ];
    // Dates to be treated as Carbon instances
    protected $dates = [
        'week_start_date',
        'week_end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    /**
     * Relationship to TaskSchedule
     */
    public function taskSchedule()
    {
        return $this->belongsTo(TaskSchedule::class, 'task_schedule_id');
    }
}
