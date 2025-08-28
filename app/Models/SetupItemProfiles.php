<?php

namespace App\Models;

use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupItemProfiles extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ModelHelpers;

    protected $table = 'setup_item_profile';

    protected $fillable = [
        'item_code',
        'item_description',
        'thickness',
        'length',
        'width',
        'height',
        'outside_diameter',
        'inside_diameter',
        'angle',
        'size',
        'specification',
        'volume',
        'weight',
        'grade',
        'volts',
        'plates',
        'part_number',
        'color',
        'uom',
        'uom_conversion_value',
        'item_group',
        'sub_item_group',
        'inventory_type',
        'active_status',
        'is_approved',
    ];
}
