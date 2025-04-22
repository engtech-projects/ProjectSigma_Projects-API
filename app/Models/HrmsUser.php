<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrmsUser extends Model implements AuthenticatableContract
{
    use HasFactory, SoftDeletes;

    public function getAuthIdentifierName()
    {
        return [
            'user_id' => 'id',
            'email' => 'email',
            'name' => 'name',
            'type' => 'user',
        ];
    }
}
