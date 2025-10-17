<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'item_id',
        'reference',
        'quantity',
        'schedule',
        'work_description',
        'duration',
        'target',
        'actual',
    ];

    protected $appends = [
        'total',
        'variance',
        'balance_to_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function item()
    {
        return $this->belongsTo(BoqItem::class);
    }

    public function dailySchedule()
    {
        return $this->hasMany(DailySchedule::class);
    }

    public function getTotalAttribute()
    {
        return $this->dailySchedule()->sum('value');
    }

    public function getVarianceAttribute()
    {
        return $this->target - $this->total;
    }

    public function getBalanceToDateAttribute()
    {
        return $this->quantity - $this->total;
    }
}
