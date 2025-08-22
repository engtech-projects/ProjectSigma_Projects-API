<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectChangeRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'requested_by',
        'request_type',
        'changes',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function projectChangeRequestApprovals()
    {
        return $this->hasMany(ProjectChangeRequestApproval::class);
    }
}
