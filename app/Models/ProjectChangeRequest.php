<?php

namespace App\Models;

use App\Traits\HasApproval;
use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectChangeRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApproval;
    protected $fillable = [
        'project_id',
        'request_type',
        'changes',
        'approvals',
        'request_status',
        'created_by',
    ];

    protected $casts = [
        'changes' => 'array',
        'approvals' => 'array',
        'created_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class);
    }

}
