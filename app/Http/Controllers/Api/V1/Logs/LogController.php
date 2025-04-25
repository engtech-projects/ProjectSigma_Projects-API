<?php

namespace App\Http\Controllers\Api\V1\Logs;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        $activities = Activity::paginate(config('services.pagination.limit'));
        return response()->json($activities);
    }
    public function all()
    {
        $activities = Activity::all();

        return $activities;
    }
}
