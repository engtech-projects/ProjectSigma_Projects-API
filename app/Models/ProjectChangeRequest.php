<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\ChangeRequestType;
use App\Enums\TssStatus;
use App\Traits\HasApproval;
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
    public function scopeOngoingTss($query)
    {
        return $query->whereHas('project', function ($q) {
            $q->where('tss_status', TssStatus::ONGOING->value);
        });
    }
    public function scopeWhereNotApproved($query)
    {
        return $query->whereNotIn('status', [ApprovalStatus::APPROVED->value]);
    }
    public function scopeDirectCostApproval($query)
    {
        return $query->where('request_type', ChangeRequestType::DIRECTCOST_APPROVAL_REQUEST->value);
    }
}
