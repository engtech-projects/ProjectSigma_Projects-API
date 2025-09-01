<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use App\Models\Project;

enum ApprovalModels: string
{
    use EnumHelper;
    case PROJECT_PROPOSAL_REQUEST = Project::class;
}
