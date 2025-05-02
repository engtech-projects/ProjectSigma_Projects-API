<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsTo(Phase::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(ResourceItem::class);
    }

    public function getUnitPriceWithQuantityAttribute()
    {
        return $this->unit_price . ' / ' . $this->unit;
    }

    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
