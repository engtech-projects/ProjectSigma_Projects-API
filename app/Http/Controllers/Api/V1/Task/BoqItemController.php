<?php

namespace App\Http\Controllers\Api\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Models\Phase;
use App\Models\BoqItem;
use App\Services\BoqItemService;
use Illuminate\Http\Request;

class BoqItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('phase_id')) {
            $phase = Phase::find($request->phase_id);
            return response()->json($phase->load('tasks.resources.resourceName'), 200);
        }
        return response()->json(BoqItem::all()->load('resources.resourceName'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        return response()->json([
            'message' => 'Project tasks added successfully.',
            'data' => BoqItemService::create($validated),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = BoqItemService::show($id);
        return response()->json([
            'message' => 'Project tasks fetched successfully.',
            'data' => new TaskCollection($task),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, BoqItem $task)
    {
        $validated = $request->validated();
        $task->update($validated);
        return response()->json([
            'message' => 'Project item has been updated',
            'data' => $task,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqItem $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Project Task has been deleted',
            'data' => $task,
        ], 200);
    }
}
