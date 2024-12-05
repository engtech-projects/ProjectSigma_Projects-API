<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\RevisionStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revision extends Model
{
    use HasFactory;

    protected $table = "revisions";

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
        ];
    }

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
