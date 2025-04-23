<?php

namespace App\Http\Controllers\Api\V1\Logs;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        $activities = Activity::all(); // Retrieve all logs

        return $activities;
    }
}
