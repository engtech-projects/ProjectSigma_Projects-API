<?php

namespace App\Models;

use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function resourceName(): HasOne
    {
        return $this->hasOne(ResourceName::class, 'id', 'resource_type');
    }

    public function scopeFilterByTaskId($query, $taskId)
    {
        return $query->where(function ($query) use ($taskId) {
            $query->where('task_id','like', "%{$taskId}%");
        });
    }

    public function scopeFilterByResourceType($query, $resourceType)
    {
        return $query->where(function ($query) use ($resourceType) {
            $query->where('resource_type','like', "%{$resourceType}%");
        });
    }
}
