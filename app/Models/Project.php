<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status' => ProjectStatus::class
    ];

    #PROJECT MODEL RELATIONS

    public function projectRelation() : BelongsTo
    {
        return $this->belongsTo(ProjectRelation::class,'id','project_id')
        ->withDefault(function(ProjectRelation $projectRelation,Project $project) {
            $projectRelation->id = $project->id;
        });
    }


    #PROJECT MODEL SCOPES
    public function scopeByProjectStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
