<?php

namespace App\Models;

use App\Enums\ProjectStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'contract_location',
        'contract_name',
        'status'
    ];

    protected $casts = [
        'status' => ProjectStatusEnum::class
    ];

    public function scopeOngoing($query)
    {
        return $query->where('status', ProjectStatusEnum::Ongoing);

    }

}
