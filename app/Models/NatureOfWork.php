<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NatureOfWork extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nature_of_work';

    protected $fillable = [
        'name',
    ];
}
