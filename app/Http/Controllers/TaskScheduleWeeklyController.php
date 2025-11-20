<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreTaskScheduleWeekRequest;
use App\Http\Requests\TaskScheduleWeeklyRequest;
use App\Models\TaskSchedule;
use App\Http\Resources\TaskScheduleWeeklyResource;
use App\Models\TaskScheduleWeek;
use Carbon\Carbon;
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
    public function store(StoreTaskScheduleWeekRequest $request)
    {
        $data = $request->validated();
        // Get parent TaskSchedule (internal_timeline)
        $taskSchedule = TaskSchedule::where('item_id', $data['item_id'])
            ->where('timeline_classification', 'internal_timeline')
            ->firstOrFail();
        $weekStart = Carbon::parse($data['week_start_date']);
        $weekEnd   = Carbon::parse($data['week_end_date']);
        // Ensure week is within parent schedule
        $scheduleStart = Carbon::parse($taskSchedule->start_date);
        $scheduleEnd   = Carbon::parse($taskSchedule->end_date);
        if ($weekStart->lt($scheduleStart) || $weekEnd->gt($scheduleEnd)) {
            return response()->json([
                'message' => 'Week dates must be within the task schedule start and end dates.'
            ], 422);
        }
        // Check for overlapping weeks
        $overlap = TaskScheduleWeek::where('task_schedule_id', $taskSchedule->id)
            ->where(function ($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('week_start_date', [$weekStart, $weekEnd])
                    ->orWhereBetween('week_end_date', [$weekStart, $weekEnd])
                    ->orWhereRaw('? BETWEEN week_start_date AND week_end_date', [$weekStart])
                    ->orWhereRaw('? BETWEEN week_start_date AND week_end_date', [$weekEnd]);
            })
            ->exists();
        if ($overlap) {
            return response()->json([
                'message' => 'The week overlaps with an existing week. Please choose a different date range.'
            ], 422);
        }
        // Store the week
        $week = TaskScheduleWeek::create([
            'task_schedule_id' => $taskSchedule->id,
            'week_start_date'  => $weekStart,
            'week_end_date'    => $weekEnd,
        ]);
        return response()->json([
            'message' => 'TaskScheduleWeek created successfully.',
            'data' => $week,
        ]);
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
    public function destroy(TaskScheduleWeek $task_schedules_weekly)
    {
        $task_schedules_weekly->delete();
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
