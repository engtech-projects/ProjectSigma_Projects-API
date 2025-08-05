<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceMetric extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'resource_id',
        'label',
        'value',
        'unit',
    ];

    public function resource()
    {
        return $this->belongsTo(ResourceItem::class);
    }

}
