<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

	protected $table = "projects";

    protected $fillable = [
		'parent_project_id',
		'contract_id',
		'code',
		'name',
		'location',
		'nature_of_work',
        'amount',
        'contract_date',
        'duration',
        'noa_date',
        'ntp_date',
		'license',
		'stage',
		'status',
		'is_original',
		'version',
        'project_identifier',
        'implementing_office',
        'current_revision_id',
    ];

	/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
			'status' => ProjectStatus::class,
			'stage' => ProjectStage::class,
			'contract_date' => 'datetime:Y-m-d',
			'noa_date' => 'datetime:Y-m-d',
			'ntp_date' => 'datetime:Y-m-d',
			'amount' => 'decimal:2',
			'is_original' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Update the project status
    public function updateStatus(ProjectStatus $status): void
    {
        $this->update(['status' => $status]);
    }
    // Archive the project
    public function archive(): void
    {
        $this->updateStatus(ProjectStatus::ARCHIVED);
    }

    // Archive the project
    public function complete(): void
    {
        $this->updateStatus(ProjectStatus::COMPLETED);
    }

	public function phases() : HasMany
	{
		return $this->hasMany(Phase::class);
	}

    public function tasks() : HasMany
	{
		return $this->hasMany(Task::class);
	}

    public function resources() : HasMany
	{
		return $this->hasMany(ResourceItem::class);
	}

	public function attachments() : HasMany
	{
		return $this->hasMany(Attachment::class);
	}

    public function revisions() : HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_project_id');
    }

    public function isOriginal()  : bool
	{
		return $this->is_original == true;
	}

    public function isApproved()  : bool
	{
		return $this->status == ProjectStatus::APPROVED->label();
	}

	# PROJECT SCOPES
	/**
     * Scope a query to only include original project.
     */
    public function scopeOriginal(Builder $query): void
    {
        $query->where('is_original', true);
    }

    /**
     * Scope a query to only include original project.
     */
    public function scopeInternal(Builder $query): void
    {
        $query->where(['is_original' => false, 'stage' => ProjectStage::AWARDED]);
    }

	/**
     * Scope a query to only include original project.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where(['status' => ProjectStatus::ONGOING]);
    }

    /**
     * Scope a query to only include original project.
     */
    public function scopeArchived(Builder $query): void
    {
        $query->onlyTrashed();
    }

}
