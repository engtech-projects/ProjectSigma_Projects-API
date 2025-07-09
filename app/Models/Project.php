<?php

namespace App\Models;

use App\Enums\MarketingStage;
use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Enums\RequestStatuses;
use App\Enums\TssStage;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use Filterable;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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
        'marketing_stage',
        'tss_stage',
        'status',
        'is_original',
        'version',
        'project_identifier',
        'implementing_office',
        'current_revision_id',
        'position',
        'designation',
        'created_by',
        'cash_flow',
    ];

    protected $casts = [
        'cash_flow' => 'array',
        'contract_date' => 'datetime:Y-m-d',
        'noa_date' => 'datetime:Y-m-d',
        'ntp_date' => 'datetime:Y-m-d',
        'amount' => 'decimal:2',
        'marketing_stage' => MarketingStage::class,
        'tss_stage' => TssStage::class,
    ];

    protected $appends = [
        'summary_of_rates',
        'summary_of_bid',
        'created_at_formatted',
        'updated_at_formatted',
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
            $query->where(DB::raw('LOWER(code)'), 'LIKE', '%' . strtolower($keyword) . '%')
                ->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($keyword) . '%');
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
        if (! $this->phases) {
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

    public function completeRequestStatus()
    {
        // Handle marketing stage flow
        if ($this->tss_stage === TssStage::Pending->value) {
            switch ($this->marketing_stage) {
                case MarketingStage::Draft->value:
                    $this->marketing_stage = MarketingStage::Proposal->value;
                    break;

                case MarketingStage::Proposal->value:
                    $this->marketing_stage = MarketingStage::Bidding->value;
                    break;

                case MarketingStage::Bidding->value:
                    $this->marketing_stage = MarketingStage::Awarded->value;
                    // Transition TSS to awarded when marketing is done
                    $this->tss_stage = TssStage::Awarded->value;
                    break;
            }
        } else {
            // Handle TSS flow
            switch ($this->tss_stage) {
                case TssStage::Awarded->value:
                    $this->tss_stage = TssStage::Archived->value;
                    break;
            }
        }

        // Set request status and persist
        $this->request_status = RequestStatuses::APPROVED->value;
        $this->save();
        $this->refresh();
    }

    public function denyRequestStatus()
    {
        // Only allow marketing to backtrack if still pending in TSS
        if ($this->tss_stage === TssStage::Pending->value) {
            switch ($this->marketing_stage) {
                case MarketingStage::Bidding->value:
                    $this->marketing_stage = MarketingStage::Proposal->value;
                    break;

                case MarketingStage::Proposal->value:
                    $this->marketing_stage = MarketingStage::Draft->value;
                    break;
            }
        }

        $this->request_status = RequestStatuses::DENIED->value;
        $this->save();
        $this->refresh();
    }

    public function getSummaryOfRatesAttribute()
    {
        $summary_of_rates = [];
        if (! $this->phases) {
            return $summary_of_rates;
        }
        foreach ($this->phases as $phase) {
            if (! $phase->tasks) {
                continue;
            }
            foreach ($phase->tasks as $task) {
                if (! $task->resources) {
                    continue;
                }
                foreach ($task->resources as $value) {
                    if ($value->quantity <= 0 || ! $value->unit) {
                        continue;
                    }
                    $resourceName = $value->resourceName->name;
                    $key = $value->description;
                    if (isset($summary_of_rates[$resourceName][$key])) {
                        $summary_of_rates[$resourceName][$key]['ids'][] = $value->id;
                    } else {
                        $summary_of_rates[$resourceName][$key] = [
                            'description' => $value->description,
                            'unit_cost' => $value->unit_cost,
                            'unit' => $value->unit,
                            'resource_name' => $value->unit_cost . ' / ' . $value->unit,
                            'total_cost' => $value->total_cost,
                            'ids' => [$value->id],
                        ];
                    }
                }
            }
        }
        return $summary_of_rates;
    }
    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y h:i A');
    }
    public function getUpdatedAtFormattedAttribute()
    {
        return Carbon::parse($this->updated_at)->format('F j, Y h:i A');
    }

    public function updateStage(ProjectStage $newStage)
    {
        // Determine if this is a TSS stage update
        $isTssUpdate = $this->tss_stage !== TssStage::Pending->value;

        // Only require approval for TSS stage updates
        if ($isTssUpdate && $this->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => 'Project must be approved to update TSS stage.',
            ]);
        }

        if (! $isTssUpdate) {
            // Handle marketing stage flow
            $flow = MarketingStage::flow();
            $current = $this->marketing_stage;
        } else {
            // Handle TSS stage flow
            $flow = TssStage::flow();
            $current = $this->tss_stage;
        }

        $currentIndex = array_search($current, $flow);
        $newIndex = array_search($newStage->value, $flow);

        if ($newIndex === false || $currentIndex === false || $newIndex !== $currentIndex + 1) {
            throw ValidationException::withMessages([
                'stage' => 'Invalid stage transition.',
            ]);
        }

        // Save the new stage
        if (!$isTssUpdate) {
            $this->marketing_stage = $newStage->value;

            if ($newStage === MarketingStage::Awarded) {
                // Automatically promote TSS to 'awarded' when marketing hits 'awarded'
                $this->tss_stage = TssStage::Awarded->value;
            }
        } else {
            $this->tss_stage = $newStage->value;
        }

        $this->save();
    }
}
