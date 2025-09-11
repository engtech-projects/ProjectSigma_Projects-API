<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashflow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "project_id",
        "percent",
        "date",
        "total_amount",
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function cashflow_items(): HasMany
    {
        return $this->hasMany(CashflowItem::class);
    }
}
