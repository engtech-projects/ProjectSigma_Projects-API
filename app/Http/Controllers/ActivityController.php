<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectActivityRequest;
use App\Http\Resources\ProjectActivityEquipmentsResource;
use App\Http\Resources\ProjectActivityManpowerResource;
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
        return ProjectActivityResource::collection($activities)
            ->additional([
                'success' => true,
                'message' => 'Activities retrieved successfully',
            ]);
    }

    public function createProjectActivity(Project $project, ProjectActivityRequest $request)
    {
        $activity = $project->activity()->create($request->validated());
        return ProjectActivityResource::make($activity)
            ->additional([
                'success' => true,
                'message' => 'Activity created successfully',
            ]);
    }

    public function update($activityId, ProjectActivityRequest $request)
    {
        $activity = $this->activity::findOrFail($activityId);
        $activity->update($request->validated());
        return ProjectActivityResource::make($activity)
            ->additional([
                'success' => true,
                'message' => 'Activity updated successfully',
            ]);
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
    public function getProjectActivityEquipments(Project $project)
    {
        $equipment = $project->equipmentRentals();
        return ProjectActivityEquipmentsResource::collection($equipment)
            ->additional([
                'success' => true,
                'message' => 'Equipment retrieved successfully',
            ]);
    }
    public function getProjectActivityManpower(Project $project)
    {
        $manpower = $project->manpower();
        return ProjectActivityManpowerResource::collection($manpower)
            ->additional([
                'success' => true,
                'message' => 'Manpower retrieved successfully',
            ]);
    }
}
