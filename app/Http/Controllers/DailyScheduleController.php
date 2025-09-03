<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityDailyScheduleRequest;
use App\Http\Resources\ActivityDailyScheduleResource;
use App\Models\Activity;
use App\Models\DailySchedule;

class DailyScheduleController extends Controller
{
    protected $dailySchedule;
    public function __construct()
    {
        $this->dailySchedule = DailySchedule::class;
    }

    public function getDailySchedule(Activity $activity)
    {
        $dailySchedule = $activity->dailySchedule()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule retrieved successfully',
            'daily_schedule' => ActivityDailyScheduleResource::collection($dailySchedule),
        ], 200);
    }

    public function updateOrCreateDailySchedule(Activity $activity, ActivityDailyScheduleRequest $request)
    {
        $dailySchedule = $activity->dailySchedule()->updateOrCreate($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule updated successfully',
            'daily_schedule' => ActivityDailyScheduleResource::make($dailySchedule),
        ], 200);
    }

    public function destroy($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::findOrFail($dailyScheduleId);
        $dailySchedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule deleted successfully',
        ], 200);
    }

    public function restore($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::withTrashed()->findOrFail($dailyScheduleId);
        $dailySchedule->restore();
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule restored successfully',
            'daily_schedule' => $dailySchedule,
        ], 200);
    }
}
