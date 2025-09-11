<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashflowItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "cashflow_id",
        "item_id",
    ];

    public function cashflow(): HasMany
    {
        return $this->hasMany(Cashflow::class);
    }

    public function item(): HasMany
    {
        return $this->hasMany(BoqItem::class);
    }
}
