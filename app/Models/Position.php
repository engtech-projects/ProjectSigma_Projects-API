<?php

namespace App\Models;

use App\Http\Traits\ModelHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes, ModelHelper;

    protected $fillable = [
        'name',
        'description',
    ];

    public function projectDesignations()
    {
        return $this->hasMany(ProjectDesignation::class);
    }
}
