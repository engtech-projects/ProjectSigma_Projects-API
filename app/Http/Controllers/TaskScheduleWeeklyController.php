<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskScheduleWeeklyRequest;
use App\Models\TaskSchedule;
use App\Http\Resources\TaskScheduleResource;
use App\Http\Resources\TaskScheduleWeeklyResource;
use App\Models\TaskScheduleWeek;

class TaskScheduleWeeklyController extends Controller
{
    public function index()
    {
        $weeklyTaskSchedules = TaskScheduleWeek::all();
        return TaskScheduleWeeklyResource::collection($weeklyTaskSchedules)
            ->additional([
                'success' => true,
                'message' => 'Weekly task schedules fetched successfully',
            ]);
    }
    public function store(TaskScheduleWeek $weekly, TaskScheduleWeeklyRequest $request)
    {
        $weekly->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Weekly task schedule created successfully.',
        ], 200);
    }
    public function show(TaskScheduleWeek $weekly)
    {
        return TaskScheduleWeeklyResource::make($weekly)
            ->additional([
                'success' => true,
                'message' => 'Weekly task schedule fetched successfully',
            ]);
    }
    public function update(TaskScheduleWeek $weekly, TaskScheduleWeeklyRequest $request)
    {
        $weekly->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Weekly task schedule updated successfully.',
        ], 200);
    }
    public function destroy(TaskScheduleWeek $weekly)
    {
        $weekly->delete();
        return response()->json([
            'success' => true,
            'message' => 'Weekly task schedule deleted successfully',
        ], 200);
    }
    public function getWeeklyScheduleByTaskScheduleId(TaskSchedule $taskSchedule)
    {
        $weeklyTaskSchedules = TaskScheduleWeek::where('task_schedule_id', $taskSchedule->id)->get();
        return TaskScheduleWeeklyResource::collection($weeklyTaskSchedules)
            ->additional([
                'success' => true,
                'message' => 'Weekly task schedules fetched successfully',
            ]);
    }
}
