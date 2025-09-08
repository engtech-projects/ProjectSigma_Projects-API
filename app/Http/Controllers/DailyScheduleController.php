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
        return ActivityDailyScheduleResource::collection($dailySchedule)
            ->additional([
                'success' => true,
                'messsage' => 'Daily schedule retrieved successfully',
            ]);
    }

    public function updateOrCreateDailySchedule(Activity $activity, ActivityDailyScheduleRequest $request)
    {
        $dailySchedule = $activity->dailySchedule()->updateOrCreate($request->validated());
        return ActivityDailyScheduleResource::make($dailySchedule)
            ->additional([
                'success' => true,
                'message' => 'Daily schedule updated successfully',
            ]);
    }

    public function destroy($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::findOrFail($dailyScheduleId);
        $dailySchedule->delete();
        return ActivityDailyScheduleResource::make($dailySchedule)
            ->additional([
                'success' => true,
                'message' => 'Daily schedule deleted successfully',
            ]);
    }

    public function restore($dailyScheduleId)
    {
        $dailySchedule = $this->dailySchedule::withTrashed()->findOrFail($dailyScheduleId);
        $dailySchedule->restore();
        return ActivityDailyScheduleResource::make($dailySchedule)
            ->additional([
                'success' => true,
                'message' => 'Daily schedule restored successfully',
            ]);
    }
}
