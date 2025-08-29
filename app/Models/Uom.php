<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'uom';

    protected $fillable = [
        'name',
        'symbol',
        'description',
        'source_id',
    ];
    public function getNameWithSymbolAttribute()
    {
        return "{$this->name} ({$this->symbol})";
    }
}
