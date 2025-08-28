<?php

namespace App\Exceptions;

use Exception;

class ScheduleConflictException extends Exception
{
    public $conflicts;
    public $suggestedSlots;

    public function __construct($conflicts, $suggestedSlots)
    {
        parent::__construct('Schedule conflict detected. Suggested available slots provided.');
        $this->conflicts = $conflicts;
        $this->suggestedSlots = $suggestedSlots;
    }
}
