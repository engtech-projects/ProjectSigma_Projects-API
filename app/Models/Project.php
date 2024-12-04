<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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

	public function attachments() : HasMany
	{
		return $this->hasMany(Attachment::class);
	}

    public function isOriginal()  : bool
	{
		return $this->is_original == true;
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
    public function scopeRevised(Builder $query): void
    {
        $query->where('is_original', false);
    }

}
