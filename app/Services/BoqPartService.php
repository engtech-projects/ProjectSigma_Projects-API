<?php

namespace App\Services;

use App\Models\BoqPart;
use Illuminate\Support\Facades\DB;

class BoqPartService
{
    public static function withPagination($request)
    {
        return BoqPart::query()
            ->when(isset($request['key']), fn ($q) => $q->where('name', 'LIKE', "%{$request['key']}%"))
            ->when(isset($request['project_id']), fn ($q) => $q->where('project_id', $request['project_id']))
            ->with('tasks')
            ->paginate(config('services.pagination.limit'));
    }

    public static function withProjects($request)
    {
        $query = BoqPart::query();
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
        return BoqPart::all();
    }

    public static function create($request)
    {
        return DB::transaction(function () use ($request) {
            $data = BoqPart::create($request);

            return $data;
        });
    }

    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = BoqPart::findOrFail($id);
            $data->fill($request)->save();

            return $data;
        });
    }

    public static function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $data = BoqPart::findOrFail($id);
            $data->delete();

            return $data;
        });
    }

    public static function show($id)
    {
        return BoqPart::findOrFail($id);
    }
}
