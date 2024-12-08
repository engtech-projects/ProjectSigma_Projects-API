<?php

namespace App\Enums;

enum RevisionStatus:string
{
	case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';

	public function label(): string {
        return match($this) {
			RevisionStatus::DRAFT => 'draft',
			RevisionStatus::ARCHIVED => 'archived',
            RevisionStatus::ACTIVE => 'active',
            RevisionStatus::REJECTED => 'rejected',
        };
    }
}
