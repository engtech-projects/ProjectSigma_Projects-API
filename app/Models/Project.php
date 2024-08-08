<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'contract_id',
        'contract_location',
        'contract_name',
        'status',
        'project_code',
        'project_identifier',
        'contract_amount',
        'contract_duration',
        'implementing_office',
        'nature_of_work',
        'date_of_noa',
        'date_of_contract',
        'date_of_ntp',
        'license',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'date_of_noa' => 'date:Y-m-d'
    ];

    #PROJECT MODEL RELATIONS

    #PROJECT MODEL SCOPES
    public function scopeByProjectStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
