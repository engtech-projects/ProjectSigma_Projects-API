<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    const STATUS_COMPLETED = ProjectStatus::COMPLETED;
    const STATUS_ONGOING = ProjectStatus::ONGOING;

    protected $fillable = [
        'contract_id',
        'contract_location',
        'contract_name',
        'status'
    ];

    protected $casts = [
        'status' => ProjectStatus::class
    ];

    public function scopeByProjectStatus($query,$status)
    {
        return $query->where('status', $status);
    }

}
