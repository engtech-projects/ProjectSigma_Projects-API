<?php

namespace App\Services;

use App\Models\Phase;
use Illuminate\Support\Facades\DB;

class BOQPartService
{
    public static function withPagination($request)
    {
        $query = Phase::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });
        $query = $query->when(isset($request['project_id']), function ($query) use ($request) {
            return $query->where('project_id', $request['project_id']);
        });
        $query->with('tasks');

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function withProjects($request)
    {
        $query = Phase::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });
        $query = $query->when(isset($request['project_id']), function ($query) use ($request) {
            return $query->where('project_id', $request['project_id']);
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function all()
    {
        return Phase::all();
    }

    public static function create($request)
    {
        return DB::transaction(function () use ($request) {
            $data = Phase::create($request);

            return $data;
        });
    }

    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = Phase::findOrFail($id);
            $data->fill($request)->save();

            return $data;
        });
    }

    public static function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Phase::findOrFail($id);
            $data->delete();

            return $data;
        });
    }

    public static function show($id)
    {
        return Phase::findOrFail($id);
    }
}
