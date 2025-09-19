<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Traits\FormatsNumbers;
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
    use FormatsNumbers;
    protected $table = 'tasks';
    protected $fillable = [
        'phase_id',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price',
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
        return $this->resources()->where('resource_type', 'materials')
            ->sum('total_cost');
    }
    public function getTotalDirectCostAttribute()
    {
        return $this->resources()->sum('total_cost');
    }
    public function getTotalEquipmentAmountAttribute()
    {
        return $this->resources()->where('resource_type', 'equipment_rental')
            ->sum('total_cost');
    }
    public function getTotalLaborAmountAttribute()
    {
        return $this->resources()->where('resource_type', 'labor_expense')
            ->sum('total_cost');
    }
    public function getTotalFuelOilAmountAttribute()
    {
        return $this->resources()->where('resource_type', 'fuel_oil_cost')
            ->sum('total_cost');
    }
    public function getTotalOverheadAmountAttribute()
    {
        return $this->resources()->where('resource_type', 'overhead_cost')
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
}
