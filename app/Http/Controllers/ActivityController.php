<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectActitvityRequest;
use App\Http\Resources\ProjectActivityResource;
use App\Models\Activity;
use App\Models\Project;

class ActivityController extends Controller
{
    protected $activity;
    public function __construct()
    {
        $this->activity = Activity::class;
    }
    public function projectActivities(Project $project)
    {
        $activities = $project->activity()->get();
        return response()->json([
            'success' => true,
            'message' => 'Activities retrieved successfully',
            'activities' => ProjectActivityResource::collection($activities),
        ], 200);
    }

    public function createProjectActivity(Project $project, ProjectActitvityRequest $request)
    {
        $activity = $project->activity()->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'activity' => ProjectActivityResource::make($activity),
        ], 201);
    }

    public function update($activityId, ProjectActitvityRequest $request)
    {
        $activity = $this->activity::findOrFail($activityId);
        $activity->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'activity' => ProjectActivityResource::make($activity),
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

    public function restore($activityId)
    {
        $activity = $this->activity::withTrashed()->findOrFail($activityId);
        $activity->restore();
        return response()->json([
            'success' => true,
            'message' => 'Activity restored successfully',
        ], 200);
    }
}
