<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailySchedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'activity_id',
        'day',
        'value',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
