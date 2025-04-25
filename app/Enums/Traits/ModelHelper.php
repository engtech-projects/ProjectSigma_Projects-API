<?php

namespace App\Http\Traits;

use Illuminate\Support\Carbon;

trait ModelHelper
{
    /**
     * ==================================================
     * MODEL ATTRIBUTES
     * ==================================================
     */
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y h:i A');
    }

    public function getCreatedAtDateHumanAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }

    public function getCreatedAtTimeHumanAttribute()
    {
        return Carbon::parse($this->created_at)->format('h:i A');
    }
}
