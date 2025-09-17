<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'family_name',
    ];

    public function employee(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function projectDesignations(): HasMany
    {
        return $this->hasMany(ProjectDesignation::class);
    }
}
