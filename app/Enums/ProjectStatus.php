<?php

namespace App\Enums;

enum ProjectStatus:string
{
	case OPEN = 'open';
	case PUBLISHED = 'published';
	case ACTIVE = 'active';
	case ONHOLD = 'on-hold';
    case COMPLETED = 'completed';

	public function label(): string {
        return match($this) {
			ProjectStatus::OPEN => 'open',
			ProjectStatus::PUBLISH => 'published',
			ProjectStatus::ACTIVE => 'active',
			ProjectStatus::ONHOLD => 'on-hold',
			ProjectStatus::COMPLETED => 'completed',
        };
    }
}
