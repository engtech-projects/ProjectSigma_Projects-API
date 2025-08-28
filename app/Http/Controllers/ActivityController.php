<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityDailyScheduleRequest;
use App\Http\Requests\ProjectActitvityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $activity;
    public function __construct()
    {
        $this->activity = Activity::class;
    }

    public function update($activityId, ProjectActitvityRequest $request)
    {
        $activity = $this->activity::findOrFail($activityId);
        $activity->update([
            'item_id' => $request->item_id,
            'reference' => $request->reference,
            'quantity' => $request->quantity,
            'schedule' => $request->schedule,
            'work_description' => $request->work_description,
            'duration' => $request->duration,
            'target' => $request->target,
            'actual' => $request->actual,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'activity' => $activity,
        ], 200);
    }

    public function destroy($activityId)
    {
        $activity = $this->activity::findOrFail($activityId);
        $activity->delete();
        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully',
        ], 200);
    }

    public function projectActivities($projectId)
    {
        $activities = $this->activity::where('project_id', $projectId)->with('item')->get();
        return response()->json([
            'success' => true,
            'message' => 'Activities retrieved successfully',
            'activities' => $activities,
        ], 200);
    }

    public function createProjectActivity($projectId, ProjectActitvityRequest $request)
    {
        $activity = $this->activity::create([
            'project_id' => $projectId,
            'item_id' => $request->item_id,
            'reference' => $request->reference,
            'quantity' => $request->quantity,
            'schedule' => $request->schedule,
            'work_description' => $request->work_description,
            'duration' => $request->duration,
            'target' => $request->target,
            'actual' => $request->actual,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'activity' => $activity,
        ], 201);
    }

    public function restore($activityId)
    {
        $activity = $this->activity::withTrashed()->findOrFail($activityId);
        $activity->restore();
        return response()->json([
            'success' => true,
            'message' => 'Activity restored successfully',
        ], 200);
    }

    public function getDailySchedule($activityId)
    {
        $activity = $this->activity::findOrFail($activityId);
        $dailySchedule = $activity->dailySchedule;
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule retrieved successfully',
            'daily_schedule' => $dailySchedule,
        ], 200);
    }

    public function updateOrCreateDailySchedule($activityId, ActivityDailyScheduleRequest $request)
    {
        $activity = $this->activity::findOrFail($activityId);
        $activity->dailySchedule()->updateOrCreate([
            'activity_id' => $activityId,
            'day' => $request->day,
            'value' => $request->value,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Daily schedule updated successfully',
        ], 200);
    }

}
