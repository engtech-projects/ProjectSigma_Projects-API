<?php

namespace App\Models;

use App\Enums\RevisionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Revision extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'revisions';

    protected $fillable = [
        'project_id',
        'project_uuid',
        'data',
        'comments',
        'status',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RevisionStatus::class,
            'data' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function nextVersion($projectId)
    {
        return static::where('project_id', $projectId)->max('version') + 1;
    }

    public function scopeWhereProjectCode(Builder $query, string $code): Builder
    {
        return $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.code')) LIKE ?", ["%{$code}%"]);
    }

    public function scopeWhereProjectName(Builder $query, string $name): Builder
    {
        return $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')) LIKE ?", ["%{$name}%"]);
    }

    public function scopeProjectKey(Builder $query, string $projectKey): Builder
    {
        return $query->where(function ($q) use ($projectKey) {
            $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.code')) LIKE ?", ["%{$projectKey}%"])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')) LIKE ?", ["%{$projectKey}%"]);
        });
    }
}
