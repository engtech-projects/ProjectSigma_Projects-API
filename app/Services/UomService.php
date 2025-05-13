<?php

namespace App\Services;

use App\Models\Uom;

class UomService
{
    public static function all()
    {
        return Uom::all();
    }

    public static function withPaginate($request)
    {
        $query = Uom::query();
        $query = $query->when(isset($request['key']), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request['key']}%");
        });

        return $query->paginate(config('services.pagination.limit'));
    }
}
