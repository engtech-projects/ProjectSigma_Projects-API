<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemProfileListResource;
use App\Http\Resources\UomListResource;
use App\Models\SetupItemProfiles;
use App\Models\Uom;

class SetupListsController extends Controller
{
    public function getUomList()
    {
        $fetch = Uom::latest()
            ->paginate(config('app.pagination.per_page'));
        return UomListResource::collection($fetch)
            ->additional([
                'success' => true,
                'message' => 'Departments Successfully Fetched.',
            ]);
    }
    public function getItemProfileList()
    {
        $fetch = SetupItemProfiles::latest()
            ->paginate(config('app.pagination.per_page'));
        return ItemProfileListResource::collection($fetch)
            ->additional([
                'success' => true,
                'message' => 'Departments Successfully Fetched.',
            ]);
    }
}
