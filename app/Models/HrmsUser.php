<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrmsUser extends Authenticatable implements AuthenticatableContract
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    // Hide sensitive fields
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
