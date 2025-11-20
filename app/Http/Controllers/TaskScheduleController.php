<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TimelineClassification;
use App\Exceptions\ScheduleConflictException;
use App\Http\Requests\CreateTaskScheduleRequest;
use App\Http\Requests\FilterTaskScheduleRequest;
use App\Http\Requests\UpdateTaskScheduleRequest;
use App\Http\Resources\ProjectTaskScheduleResource;
use App\Http\Resources\TaskScheduleResource;
use App\Models\BoqItem;
use App\Models\Project;
use App\Models\TaskSchedule;
use App\Services\TaskScheduleService;
use Illuminate\Validation\ValidationException;

class TaskScheduleController extends Controller
{
    protected TaskScheduleService $taskScheduleService;
    public function __construct(TaskScheduleService $taskScheduleService)
    {
        $this->taskScheduleService = $taskScheduleService;
    }
    public function filterProjectTaskSchedules(FilterTaskScheduleRequest $request)
    {
        try {
            $taskSchedules = $this->taskScheduleService->searchAndFilter($request->validated());
            return ProjectTaskScheduleResource::collection($taskSchedules)->additional([
                'success' => true,
                'message' => 'Task schedules of projects retrieved successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
    public function getAllTaskScheduleByProject(Project $project)
    {
        $taskSchedules = $project->with('phases.tasks.schedule.weeks')->findOrFail($project->id);
        return ProjectTaskScheduleResource::make($taskSchedules)
            ->additional([
                'success' => true,
                'message' => 'Task schedules of a project retrieved successfully.',
            ]);
    }
    public function store(CreateTaskScheduleRequest $request)
    {
        $data = $request->validated();
        // Convert to enum or default to INTERNAL
        $timelineValue = TimelineClassification::INTERNAL_TIMELINE->value; // the actual string stored in DB
        // Get parent task name
        $task = BoqItem::findOrFail($data['item_id']);
        // Build payload
        $payload = [
            'timeline_classification' => $timelineValue,
            'item_id'                 => $data['item_id'],
            'name'                    => $task->name,
            'start_date'              => $data['start_date'] ?? null,
            'end_date'                => $data['end_date'] ?? null,
            // Auto defaults
            'status'         => TaskStatus::PENDING->value,
            'weight_percent' => 0,
        ];
        // Calculate duration ONLY when both dates exist
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $payload['duration_days'] = $this->durationDays(
                $data['start_date'],
                $data['end_date']
            );
        }
        // Update record ONLY when BOTH match:
        // - same item_id
        // - same timeline_classification
        $schedule = TaskSchedule::updateOrCreate(
            [
                'item_id'                 => $data['item_id'],
                'timeline_classification' => $timelineValue,
            ],
            $payload
        );
        return response()->json([
            'message' => $schedule->wasRecentlyCreated
                ? 'Task schedule created'
                : 'Task schedule updated',
            'data' => $schedule
        ]);
    }
    private function durationDays($start, $end)
    {
        return (strtotime($end) - strtotime($start)) / 86400 + 1;
    }
    public function show(TaskSchedule $taskSchedule)
    {
        return TaskScheduleResource::make($taskSchedule)
            ->additional([
                'success' => true,
                'message' => 'Task schedule fetched successfully',
            ]);
    }
    public function update(UpdateTaskScheduleRequest $request, $id)
    {
        try {
            $updatedTaskSchedule = $this->taskScheduleService->updateTaskSchedule($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Task schedule updated successfully.',
                'data' => $updatedTaskSchedule,
            ], 200);
        } catch (ScheduleConflictException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'conflicts' => $e->conflicts,
                'suggested_slots' => $e->suggestedSlots,
            ], 422);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
    public function destroy(TaskSchedule $taskSchedule)
    {
        $taskSchedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task schedule deleted successfully',
        ]);
    }
}
