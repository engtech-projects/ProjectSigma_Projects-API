<?php

namespace App\Models;

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

    protected $appends = [
        'unit_price_with_quantity',
        'total_price',
        'resource_item_total',
        'ocm',
        'contractors_profit',
        'vat',
        'grand_total',
        'unit_cost_per',
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

    public function getUnitPriceWithQuantityAttribute()
    {
        return $this->unit_price . ' / ' . $this->unit . ' / ' . $this->quantity;
    }

    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }

    public function getEachResourceItemTotalAttribute()
    {
        $resource = [];
        foreach ($this->resources as $key => $value) {
            if (! isset($resource[$value->resourceName->name]['total_cost'])) {
                $resource[$value->resourceName->name]['total_cost'] = 0;
            }
            $resource[$value->resourceName->name]['total_cost'] += $value->total_cost;
        }

        return $resource;
    }

    public function getResourceItemTotalAttribute()
    {
        return $this->resources->sum('total_cost');
    }

    public function getOcmAttribute()
    {
        return $this->resource_item_total > 0 ? $this->resource_item_total * 0.1 : 0;
    }

    public function getContractorsProfitAttribute()
    {
        return $this->resource_item_total > 0 ? $this->resource_item_total * 0.1 : 0;
    }

    public function getVatAttribute()
    {
        return $this->resource_item_total > 0 ? 0.12 * ($this->resource_item_total + $this->ocm + $this->contractors_profit) : 0;
    }

    public function getGrandTotalAttribute()
    {
        return $this->resource_item_total > 0 ? $this->resource_item_total + $this->ocm + $this->contractors_profit + $this->vat : 0;
    }

    public function getUnitCostPerAttribute()
    {
        if ($this->quantity == 0) {
            return 0;
        }

        return round($this->grand_total / $this->quantity, 2);
    }
}
