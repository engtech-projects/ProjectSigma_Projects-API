<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectDatesResource;
use App\Http\Resources\ProjectsNamesResource;
use App\Models\Project;

class CalendarController extends Controller
{
    public function fetchProjectsNamesForCalendar(Project $project)
    {
        $projects = $project->fetchProjectsNames()->get();
        return ProjectsNamesResource::collection($projects)
            ->additional([
                'status' => 'success',
                'message' => 'Projects names retrieved successfully',
            ]);
    }
    public function showProjectCalendarDates(Project $project)
    {
        return ProjectDatesResource::make($project)
            ->additional([
                'status' => 'success',
                'message' => 'Project task schedules retrieved successfully',
            ]);
    }
}
