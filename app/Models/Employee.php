<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

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

    // public function assignments() : HasMany
    // {
    //     return $this->hasMany(User::class);
    // }

    /**
     * Get the formatted full name of the employee.
     *
     * @param  string  $format  The format of the name (default: 'first_last').
     *                          Options: 'first_last', 'last_first', 'initials'
     * @return string
     */
    public function getFormattedFullname($format = 'first_last')
    {
        switch ($format) {
            case 'last_first':
                $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)).'.' : '';

                return implode(' ', array_filter([
                    $this->family_name,
                    $this->first_name,
                    $middleInitial,
                ]));
            case 'initials':
                $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)).'.' : '';

                return implode(' ', array_filter([
                    $this->first_name,
                    $middleInitial,
                    $this->family_name,
                ]));
            case 'first_last':
            default:
                $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)).'.' : '';

                return implode(' ', array_filter([
                    $this->first_name,
                    $middleInitial,
                    $this->family_name,
                ]));
        }
    }

    public function projectDesignations(): HasMany
    {
        return $this->hasMany(ProjectDesignation::class);
    }
}
