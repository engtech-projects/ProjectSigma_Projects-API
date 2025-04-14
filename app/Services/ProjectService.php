<?php

namespace App\Services;

use App\Models\Phase;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function create(array $attr)
    {
        return DB::transaction(function () use ($attr) {

            $data = Project::create($attr);
            $data->projectDesignation()->create([
                'employee_id' => $attr['employee_id'],
            ]);

            return new JsonResponse([
                'message' => 'Project created successfully.',
                'data' => $data,
            ], 201);
        });
    }

    public function update(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            $project->fill($attr)->save();

            return new JsonResponse([
                'message' => 'Project updated successfully.',
                'data' => $project,
            ], 200);
        });
    }

    public function assignTeam(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            foreach ($attr as $personnel) {
                $personnel['project_id'] = $project->id;
                $project->team()->updateOrCreate(
                    ['id' => $personnel['id'] ?? null], // Match id
                    $personnel // Data to update or create
                );
            }

            return new JsonResponse([
                'message' => 'Team assigned successfully.',
                'data' => $project->team()->get(),
            ], 200);
        });
    }

    public function addPhases(Project $project, array $attr)
    {
        DB::transaction(function () use ($project, $attr) {
            foreach ($attr as $phase) {
                $project->phases()->updateOrCreate(
                    ['id' => $phase['id'] ?? null], // Match id
                    $phase // Data to update or create
                );
            }
        });

        return new JsonResponse([
            'message' => 'Phases added successfully.',
            'data' => $project->phases()->get(),
        ], 200);
    }

    public function addTasks(Phase $phase, array $attr)
    {
        DB::transaction(function () use ($phase, $attr) {
            foreach ($attr as $task) {
                $task['project_id'] = $phase->project_id;
                $phase->tasks()->updateOrCreate(
                    ['id' => $task['id'] ?? null], // Match id
                    $task // Data to update or create
                );
            }
        });

        return new JsonResponse([
            'message' => 'Tasks added successfully.',
            'data' => $phase->tasks()->get(),
        ], 200);
    }

    public function addResources(Task $task, array $attr)
    {
        DB::transaction(function () use ($task, $attr) {

            DB::transaction(function () use ($task, $attr) {
                foreach ($attr as $item) {
                    $item['project_id'] = $task->project_id;
                    $task->resources()->updateOrCreate(
                        ['id' => $item['id'] ?? null], // Match id
                        $item // Data to update or create
                    );
                }

            });

            return new JsonResponse([
                'message' => 'Resources added successfully.',
                'data' => $task->resources()->get(),
            ], 200);
        });
        return new JsonResponse([
            'message' => 'Resources added successfully.',
            'data' => [],
        ], 200);
    }
}
