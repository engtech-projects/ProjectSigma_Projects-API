<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ResourceType;
use App\Enums\Traits\FormatNumbers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BoqItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FormatNumbers;
    protected $table = 'tasks';
    protected $fillable = [
        'phase_id',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'draft_unit_price',
        'draft_amount',
        'amount',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
        static::saving(function ($model) {
            $quantity = $model->quantity ?? 0;
            $draft_unit_price = $model->draft_unit_price ?? 0;
            $model->draft_amount = $quantity * $draft_unit_price;
            $model->amount = $model->quantity * $model->unit_price;
        });
        static::saving(function ($model) {

        });
    }
    public function phase(): BelongsTo
    {
        return $this->belongsTo(BoqPart::class, 'phase_id', 'id');
    }
    public function resources(): HasMany
    {
        return $this->hasMany(ResourceItem::class, 'task_id', 'id');
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(TaskSchedule::class, 'item_id', 'id');
    }
    public function project()
    {
        return $this->hasOneThrough(
            Project::class,
            BoqPart::class,
            'id',
            'id',
            'phase_id',
            'project_id'
        );
    }
    public function getFormattedQuantityAttribute()
    {
        return $this->formatted('quantity');
    }
    protected function getCanUpdateTotalAmountAttribute()
    {
        $status = $this->phase?->project?->status;
        return $status == ProjectStatus::PENDING->value;
    }
    public function getUnitPriceWithUnitAttribute()
    {
        return $this->unit_price . ' / ' . $this->unit;
    }
    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
    public function getResourceTotalsAttribute()
    {
        return collect($this->resources)
            ->groupBy('resource_type')
            ->map(function ($group) {
                return [
                    'total_cost' => $group->sum('total_cost'),
                ];
            })
            ->toArray();
    }
    public function getResourceItemTotalAttribute()
    {
        return $this->resources->sum('total_cost');
    }
    public function getTotalMaterialsAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::MATERIALS->value)
            ->sum('total_cost');
    }
    public function getTotalDirectCostAttribute()
    {
        return $this->resources()->sum('total_cost');
    }
    public function getTotalEquipmentAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::EQUIPMENT_RENTAL->value)
            ->sum('total_cost');
    }
    public function getTotalLaborAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::LABOR_EXPENSE->value)
            ->sum('total_cost');
    }
    public function getTotalFuelOilAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::FUEL_OIL_COST->value)
            ->sum('total_cost');
    }
    public function getTotalGovernmentPremiumsAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::GOVERNMENT_PREMIUMS->value)
            ->sum('total_cost');
    }
    public function getTotalMiscellaneousCostAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::MISCELLANEOUS_COST->value)
            ->sum('total_cost');
    }
    public function getTotalOtherExpensesAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::OTHER_EXPENSES->value)
            ->sum('total_cost');
    }
    public function getTotalProjectAllowanceAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::PROJECT_ALLOWANCE->value)
            ->sum('total_cost');
    }
    public function getTotalOverheadAmountAttribute()
    {
        return $this->resources()->where('resource_type', ResourceType::OVERHEAD_COST->value)
            ->sum('total_cost');
    }
    public function getOcmAttribute()
    {
        $total = collect($this->resource_totals)
            ->sum('total_cost');
        return $total > 0 ? $total * 0.1 : 0;
    }
    public function getContractorsProfitAttribute()
    {
        $total = collect($this->resource_totals)->sum('total_cost');
        return $total > 0 ? $total * 0.1 : 0;
    }
    public function getVatAttribute()
    {
        $total = collect($this->resource_totals)->sum('total_cost');
        return $total > 0
            ? 0.12 * ($total + $this->ocm + $this->contractors_profit)
            : 0;
    }
    public function getGrandTotalAttribute()
    {
        $total = collect($this->resource_totals)->sum('total_cost');
        return $total > 0
            ? $total + $this->ocm + $this->contractors_profit + $this->vat
            : 0;
    }
    public function getUnitCostPerAttribute()
    {
        if ($this->quantity == 0) {
            return 0;
        }
        return round($this->grand_total / $this->quantity, 2);
    }
    public static function updateTotalProject(int $projectId): void
    {
        $totalAmount = self::whereHas('phase', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->sum('amount');
        Project::where('id', $projectId)->update(['amount' => $totalAmount]);
    }
    public function recalcTaskAmount(): void
    {
        if ($this->can_update_total_amount) {
            $total = $this->resources()->sum('total_cost');
            $this->update(['amount' => $total]);
        }
    }
    /**
     * Accessors for Generation of Summary of Direct Estimate Report
     */
    public function getItemUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }
    public function getContractCostAttribute()
    {
        return [
            'quantity' => number_format($this->quantity, 3),
            'unit' => $this->unit,
            'total' => number_format($this->unit_price * $this->quantity, 2),
        ];
    }
    public function getDirectCostItemsAttribute()
    {
        return $this->resources->map(function (ResourceItem $item) {
            $totalCost = ($item->resource_type->value === "materials" && $item->setup_item_profile_id === null)
                ? 0
                : $item->total_cost;
            return [
                'resource_item_id' => $item->id,
                'setup_item_profile_id' => $item->setup_item_profile_id,
                'resource_type' => $item->resource_type,
                'total_cost' => $totalCost,
            ];
        })->values();
    }
    public function getResourceItemsTotalAttribute()
    {
        $total = $this->direct_cost_items->sum('total_cost');
        return number_format($total, 2);
    }

    public function getUnitCostPerItemAttribute()
    {
        if ($this->quantity == 0) {
            return number_format(0, 2);
        }
        $grandTotal = $this->direct_cost_items->sum('total_cost');
        $unitCostPerItem = $grandTotal / $this->quantity;
        return number_format($unitCostPerItem, 2);
    }
    public function getPercentAttribute()
    {
        if ($this->quantity == 0 || $this->unit_price == 0) {
            return number_format(0, 2) . '%';
        }
        $unitCostPerItem = ($this->direct_cost_items->sum('total_cost') / $this->quantity);
        $percent = ($unitCostPerItem / $this->unit_price) * 100;
        return number_format($percent, 2) . '%';
    }
}
