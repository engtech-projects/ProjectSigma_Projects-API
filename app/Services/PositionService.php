<?php

namespace App\Services;

use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionService
{
    public static function withPagination($request)
    {
        $query = Position::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function all()
    {
        return Position::all();
    }

    public static function create($request)
    {
        return DB::transaction(function () use ($request) {
            $data = Position::create($request);

            return $data;
        });
    }

    public static function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $data = Position::findOrFail($id);
            $data->fill($request)->save();

            return $data;
        });
    }

    public static function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Position::findOrFail($id);
            $data->delete();

            return $data;
        });
    }

    public static function show($id)
    {
        return Position::findOrFail($id);
    }
}
