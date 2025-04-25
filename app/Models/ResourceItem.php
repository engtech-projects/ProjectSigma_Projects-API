<?php

namespace App\Models;

use App\Http\Traits\ModelHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ResourceItem extends Model
{
    use HasFactory, SoftDeletes, ModelHelper;

    protected $table = 'resources';

    protected $fillable = [
        'task_id',
        'name_id',
        'description',
        'quantity',
        'unit',
        'unit_cost',
        'resource_count',
        'total_cost',
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
        return $this->belongsTo(Task::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function resourceName(): HasOne
    {
        return $this->hasOne(ResourceName::class, 'id', 'name_id');
    }
}
