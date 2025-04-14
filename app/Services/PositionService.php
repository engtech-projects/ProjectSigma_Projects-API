<?php

namespace App\Services;

use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionService
{
    public static function withPagination($request)
    {
        $validatedData = $request->validated();
        $query = Position::query();
        $query = $query->when(isset($validatedData['key']), function ($query) use ($validatedData) {
            return $query->where('name', 'LIKE', "%{$validatedData['key']}%");
        });

        return $query->paginate($validatedData['per_page'] ?? 10);
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
