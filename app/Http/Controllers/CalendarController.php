<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalendarDatesResource;
use App\Http\Resources\ProjectsNamesResource;
use App\Models\Project;

class CalendarController extends Controller
{
    public function getProjectsNamesForCalendar(Project $project)
    {
        $projects = $project->fetchProjectsNames()->get();
        return ProjectsNamesResource::collection($projects)
            ->additional([
                'status' => 'success',
                'message' => 'Projects names retrieved successfully',
            ]);
    }
    public function getProjectCalendarDates(Project $project)
    {
        return CalendarDatesResource::collection($project->taskSchedules()->get())
            ->additional([
                'success' => true,
                'message' => 'Project calendar dates with task schedules retrieved successfully',
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
            ]);
    }
}
