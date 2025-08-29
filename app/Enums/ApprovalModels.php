<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use App\Models\Project;
use App\Models\ProjectChangeRequest;

enum ApprovalModels: string
{
    use EnumHelper;
    case PROJECT_PROPOSAL_REQUEST = Project::class;
    case PROJECT_CHANGE_REQUEST = ProjectChangeRequest::class;
}
