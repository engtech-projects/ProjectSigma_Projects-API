<?php

namespace App\Models;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use Filterable, HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'projects';

    // Define which attributes should be logged
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // List of attributes to log
            ->setDescriptionForEvent(fn (string $eventName) => "Project has been {$eventName}");
    }

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
        'created_by',
        'cash_flow',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cash_flow' => 'json',
            'status' => ProjectStatus::class,
            'stage' => ProjectStage::class,
            'contract_date' => 'datetime:Y-m-d',
            'noa_date' => 'datetime:Y-m-d',
            'ntp_date' => 'datetime:Y-m-d',
            'amount' => 'decimal:2',
            'is_original' => 'boolean',
        ];
    }

    protected $appends = [
        'summary_of_rates',
        'summary_of_bid',
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

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function team(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function projectDesignation(): HasMany
    {
        return $this->hasMany(ProjectDesignation::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_project_id');
    }

    public function isOriginal(): bool
    {
        return $this->is_original == true;
    }

    public function isApproved(): bool
    {
        return $this->status == ProjectStatus::APPROVED->value;
    }

    public function isOpen(): bool
    {
        return $this->status == ProjectStatus::OPEN->value;
    }

    // PROJECT SCOPES
    /**
     * Scope a query to only include original/proposal project.
     */
    public function scopeOriginal(Builder $query)
    {
        return $query->where(['is_original' => true]);
    }

    /**
     * Scope a query to only include internal/revised projects
     */
    public function scopeRevised(Builder $query)
    {
        return $query->where(['is_original' => false]);
    }

    /**
     * Scope a query to only include ongoing projects
     */
    public function scopeOngoing(Builder $query)
    {
        return $query->where(['status' => ProjectStatus::ONGOING]);
    }

    /**
     * Scope a query to only include ongoing projects
     */
    public function scopeOpen(Builder $query)
    {
        return $query->where(['status' => ProjectStatus::OPEN]);
    }

    /**
     * Scope a query to only include ongoing projects
     */
    public function scopeSort(Builder $query, $val = 'desc')
    {
        if ($val == 'desc') {
            return $query->latest();
        }

        return $query->oldest();
    }

    /**
     * Scope a query to only include ongoing projects
     */
    public function scopeSearch(Builder $query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where(DB::raw('LOWER(code)'), 'LIKE', '%'.strtolower($keyword).'%')
                ->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%'.strtolower($keyword).'%');
        });
    }

    /**
     * Scope a query to only include archived projects.
     */
    public function scopeArchived(Builder $query)
    {
        return $query->onlyTrashed();
    }
    public function getSummaryOfBidAttribute()
    {
        $summaryOfBid = [];
        if (!$this->phases) {
            return $summaryOfBid;
        }
        foreach ($this->phases as $phase) {
            $summaryOfBid[] = [
                'part_no' => $phase->name,
                'description' => $phase->description,
                'total_amount' => $phase->tasks ? $phase->tasks->sum('amount') : 0,
            ];
        }
        return $summaryOfBid;
    }
    public function getSummaryOfRatesAttribute()
    {
        $summary_of_rates = [];
        if (!$this->phases) {
            return $summary_of_rates;
        }
        foreach ($this->phases as $phase) {
            if (!$phase->tasks) {
                continue;
            }
            foreach ($phase->tasks as $task) {
                if (!$task->resources) {
                    continue;
                }
                foreach ($task->resources as $value) {
                    if ($value->quantity <= 0 || !$value->unit) {
                        continue;
                    }
                    $resourceName = $value->resourceName->name;
                    $key = $value->description;
                    if (isset($summary_of_rates[$resourceName][$key])) {
                        $summary_of_rates[$resourceName][$key]['ids'][] = $value->id;
                    } else {
                        $summary_of_rates[$resourceName][$key] = [
                            'description' => $value->description,
                            'resource_name' => $value->unit_cost.' / '.$value->unit,
                            'total_cost' => $value->total_cost,
                            'ids' => [$value->id]
                        ];
                    }
                }
            }
        }
        return $summary_of_rates;
    }

}
