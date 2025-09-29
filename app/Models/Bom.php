<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'task_id',
        'resource_id',
        'material_name',
        'quantity',
        'unit',
        'unit_price',
        'amount',
        'additional_details',
        'source_type',
    ];

    protected static function booted()
    {
        static::saving(function ($bom) {
            $quantity   = $bom->quantity ?? 0;
            $unit_price = $bom->unit_price ?? 0;
            $bom->amount = $quantity * $unit_price;
        });
    }

    public function task()
    {
        return $this->belongsTo(BoqItem::class, 'task_id', 'id');
    }

    public function resource()
    {
        return $this->belongsTo(ResourceItem::class, 'resource_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
