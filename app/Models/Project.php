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
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\ModelHelpers;

class Project extends Model
{
    use Filterable;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;
    use ModelHelpers;

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
        'abc',
        'bid_date',
        'created_by',
        'cash_flow',
    ];

    protected $casts = [
        'cash_flow' => 'array',
        'contract_date' => 'datetime:Y-m-d',
        'noa_date' => 'datetime:Y-m-d',
        'ntp_date' => 'datetime:Y-m-d',
        'bid_date' => 'datetime:Y-m-d',
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
        $this->updateStatus(ProjectStatus::COMPLETED);
    }

    public function complete(): void
    {
        $this->updateStatus(ProjectStatus::COMPLETED);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(BoqPart::class, 'project_id', 'id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'project_id', 'id');
    }

    public function team(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class, 'project_id', 'id');
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
        return $this->status == 'approved';
    }

    public function isPending(): bool
    {
        return $this->status == ProjectStatus::PENDING->value;
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
    public function scopePending(Builder $query)
    {
        return $query->where(['status' => ProjectStatus::PENDING]);
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
            $query->where('code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('name', 'LIKE', '%' . $keyword . '%');
        });
    }

    public function scopeFilterByStage($query, ?string $stage)
    {
        return $query->when($stage, function ($q) use ($stage) {
            if ($stage === 'on-hold') {
                $q->where('status', 'on-hold');
            } else {
                $q->where(function ($query) use ($stage) {
                    $query->where('marketing_stage', $stage)
                        ->orWhere('tss_stage', $stage);
                });
            }
        });
    }

    /**
     * Scope a query to only include archived projects.
     */
    public function scopeArchived(Builder $query)
    {
        return $query->onlyTrashed();
    }

    public function scopeAwarded($query)
    {
        return $query->where('marketing_stage', MarketingStage::AWARDED)
            ->orWhere('tss_stage', TssStage::DUPA_PREPARATION);
    }

    public function scopeWithTssStage($query, $status)
    {
        return $query->where('tss_stage', $status);
    }

    public function scopeProjectKey($query, $key)
    {
        return $query->where(function ($q) use ($key) {
            $q->where('name', 'like', "%{$key}%")
                ->orWhere('code', 'like', "%{$key}%");
        });
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeFilterByTitle($query, $title)
    {
        return $query->when($title, function ($q) use ($title) {
            $q->where('name', 'like', "%{$title}%");
        });
    }

    public function scopeFilterByItemId($query, $itemId)
    {
        return $query->when($itemId, function ($q) use ($itemId) {
            $q->whereHas('phases.tasks.schedules', function ($q) use ($itemId) {
                $q->where('item_id', $itemId);
            });
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            $q->whereHas('phases.tasks.schedules', function ($q) use ($status) {
                $q->where('status', $status);
            });
        });
    }

    public function scopeFilterByDate($query, $dateFrom, $dateTo)
    {
        return $query->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereHas('phases.tasks.schedules', function ($subQuery) use ($dateFrom, $dateTo) {
                $subQuery->whereBetween('original_start', [$dateFrom, $dateTo])
                    ->orWhereBetween('original_end', [$dateFrom, $dateTo])
                    ->orWhereBetween('current_start', [$dateFrom, $dateTo])
                    ->orWhereBetween('current_end', [$dateFrom, $dateTo]);
            });
        });
    }

    public function scopeSortByField($query, $sortBy, $order)
    {
        return $query->orderBy($sortBy ?? 'updated_at', $order ?? 'desc');
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
        if ($this->tss_stage === TssStage::PENDING->value) {
            switch ($this->marketing_stage) {
                case MarketingStage::DRAFT->value:
                    $this->marketing_stage = MarketingStage::PROPOSAL->value;
                    break;

                case MarketingStage::PROPOSAL->value:
                    $this->marketing_stage = MarketingStage::BIDDING->value;
                    break;

                case MarketingStage::BIDDING->value:
                    $this->marketing_stage = MarketingStage::AWARDED->value;
                    // Transition TSS to awarded when marketing is done
                    $this->tss_stage = TssStage::DUPA_PREPARATION->value;
                    break;
            }
        } else {
            // Handle TSS flow
            switch ($this->tss_stage) {
                case TssStage::DUPA_PREPARATION->value:
                    $this->tss_stage = TssStage::COMPLETED->value;
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
        if ($this->tss_stage === TssStage::PENDING->value) {
            switch ($this->marketing_stage) {
                case MarketingStage::BIDDING->value:
                    $this->marketing_stage = MarketingStage::PROPOSAL->value;
                    break;

                case MarketingStage::PROPOSAL->value:
                    $this->marketing_stage = MarketingStage::DRAFT->value;
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
        if (!$this->phases) {
            return $summary_of_rates;
        }
        foreach ($this->phases as $phase) {
            if (! $phase->tasks) {
                continue;
            }
            foreach ($phase->tasks as $task) {
                if (!$task->resources) {
                    continue;
                }
                foreach ($task->resources as $value) {
                    if ($value->quantity <= 0 || ! $value->unit) {
                        continue;
                    }
                    $resourceName = isset($value->resource_type) ? (string) $value->resource_type->value : '';
                    $key = (string) $value->description;
                    if (isset($summary_of_rates[$resourceName][$key])) {
                        $summary_of_rates[$resourceName][$key]['ids'][] = $value->id;
                    } else {
                        $summary_of_rates[$resourceName][$key] = [
                            'description' => $value->description,
                            'unit_cost' => $value->unit_cost,
                            'unit' => $value->unit,
                            'resource_name' => $resourceName ? $resourceName : '',
                            'unit_cost_with_unit' => $value->unit_cost . ' / ' . $value->unit,
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

    public function changeRequests()
    {
        return $this->hasMany(ProjectChangeRequest::class);
    }
}
