<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashflowItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "cashflow_id",
        "item_id",
    ];

    public function cashflows(): BelongsTo
    {
        return $this->belongsTo(Cashflow::class, 'cashflow_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ResourceItem::class, 'item_id');
    }
}
