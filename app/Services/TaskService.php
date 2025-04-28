<?php

namespace App\Services;

use App\Models\Task;
use DB;

class TaskService
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function withPagination($request = [])
    {
        $query = $this->task->query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public function all($request = [])
    {
        return $this->task->all($request);
    }

    public static function create(array $attr)
    {
        // add addional required
        $attr['quantity'] = 0;
        $attr['unit'] = 0;
        $attr['unit_price'] = 0;
        $attr['amount'] = 0;

        return DB::transaction(function () use ($attr) {
            $data = Task::create($attr);

            return $data;
        });
    }

    public function update(Task $task, array $attr)
    {
        return DB::transaction(function () use ($task, $attr) {
            $task->fill($attr)->save();

            return $task;
        });
    }

    public static function show($id)
    {
        return Task::find($id)->load('resources.resourceName');
    }

    public function delete(Task $task)
    {
        return DB::transaction(function () use ($task) {
            $task->delete();

            return $task;
        });
    }
}
