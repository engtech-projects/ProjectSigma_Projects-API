<?php

namespace App\Http\Controllers;

use App\Exceptions\ScheduleConflictException;
use App\Http\Requests\CreateTaskScheduleRequest;
use App\Http\Requests\FilterTaskScheduleRequest;
use App\Http\Requests\UpdateTaskScheduleRequest;
use App\Http\Resources\ProjectTaskScheduleResource;
use App\Http\Resources\TaskScheduleResource;
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
        $taskSchedules = $project->with('phases.tasks.schedules')->findOrFail($project->id);
        return ProjectTaskScheduleResource::make($taskSchedules)
            ->additional([
                'success' => true,
                'message' => 'Task schedules of a project retrieved successfully.',
            ]);
    }
    public function store(CreateTaskScheduleRequest $request)
    {
        $storeTaskSchedule = $this->taskScheduleService->createTaskSchedule($request->validated());
        return TaskScheduleResource::make($storeTaskSchedule)->additional([
            'success' => true,
            'message' => 'Task schedule created successfully.',
        ]);
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
