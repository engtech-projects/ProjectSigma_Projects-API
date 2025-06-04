<?php

namespace App\Services;

use App\Models\ResourceItem;
use Illuminate\Support\Facades\DB;

class ResourceService
{
    public static function withPagination($request)
    {
        $query = ResourceItem::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });
        $query->with('tasks');

        return $query->get();
    }

    public static function withProjects($request)
    {
        $query = ResourceItem::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function all()
    {
        return ResourceItem::all();
    }

    public static function create($request)
    {
        return DB::transaction(function () use ($request) {
            $request['total_cost'] = $request['quantity'] * $request['unit_cost'];
            $data = ResourceItem::create($request);

            return $data;
        });
    }

    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = ResourceItem::findOrFail($id);
            $data->fill($request)->save();

            return $data;
        });
    }

    public static function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $data = ResourceItem::findOrFail($id);
            $data->delete();

            return $data;
        });
    }

    public static function show($id)
    {
        return ResourceItem::findOrFail($id);
    }
}
