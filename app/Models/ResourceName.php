<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceName extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resource_names';

    protected $fillable = [
        'name',
        'category',
        'description',
    ];
}
