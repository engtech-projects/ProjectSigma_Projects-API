<?php
namespace App\Http\Controllers;
use App\Http\Resources\ItemProfileAllListResource;
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
                'message' => 'UOM Successfully Fetched.',
            ]);
    }
    public function getItemProfileList()
    {
        $fetch = SetupItemProfiles::latest()
            ->paginate(config('app.pagination.per_page'));
        return ItemProfileListResource::collection($fetch)
            ->additional([
                'success' => true,
                'message' => 'Item Profiles Successfully Fetched.',
            ]);
    }
    public function getAllItemProfileList()
    {
        $fetch = SetupItemProfiles::latest()
            ->get();
        return ItemProfileAllListResource::collection($fetch)
            ->additional([
                'success' => true,
                'message' => 'Item Profiles Successfully Fetched.',
            ]);
    }
}
