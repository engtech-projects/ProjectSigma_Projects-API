<?php

namespace App\Services;

use App\Models\BoqItem;
use DB;

class BoqItemService
{
    protected $task;

    public function __construct(BoqItem $task)
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
        $attr['amount'] = $attr['quantity'] * $attr['unit_price'];

        return DB::transaction(function () use ($attr) {
            $data = BoqItem::create($attr);

            return $data;
        });
    }

    public function update(BoqItem $task, array $attr)
    {
        return DB::transaction(function () use ($task, $attr) {
            $task->fill($attr)->save();

            return $task;
        });
    }

    public static function show($id)
    {
        return BoqItem::findOrFail($id)->load('resources');
    }

    public function delete(BoqItem $task)
    {
        return DB::transaction(function () use ($task) {
            $task->delete();

            return $task;
        });
    }
}
