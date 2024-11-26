<?php

namespace App\Enums;

enum ProjectStage:string
{
	case PROPOSAL = 'proposal';
	case EVALUATION = 'evaluation';
    case AWARD = 'award';
	case EXECUTION = 'execution';
	case COMPLETION = 'completion';

	public function label(): string {
        return match($this) {
			ProjectStage::PROPOSAL => 'proposal',
			ProjectStage::EVALUATION => 'evaluation',
			ProjectStage::AWARD => 'award',
			ProjectStage::EXECUTION => 'execution',
			ProjectStage::COMPLETION => 'completion',
        };
    }
}
