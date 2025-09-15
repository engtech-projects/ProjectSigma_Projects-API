<?php

namespace App\Http\Controllers\Api\V1\Uom;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUomRequest;
use App\Http\Requests\UpdateUomRequest;
use App\Http\Resources\UomListAllResource;
use App\Http\Resources\UomListResource;
use App\Models\Uom;

class UomController extends Controller
{
    public function index()
    {
        $perPage = config('services.pagination.limit', 10);
        $data = Uom::latest()->paginate($perPage);
        return UomListResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Units fetched successfully.',
            ]);
    }
    public function all()
    {
        $data = Uom::latest()->get();
        return UomListAllResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Data fetched successfully.',
            ]);
    }
    public function store(StoreUomRequest $request)
    {
        return response()->json([
            'message' => 'UOM added successfully.',
            'data' => Uom::create($request->validated()),
        ], 201);
    }
    public function update(UpdateUomRequest $request, Uom $resource)
    {
        $validatedRequest = $request->validated();
        $resource->update($validatedRequest);
        return response()->json([
            'message' => 'UOM updated successfully.',
            'data' => $resource,
        ], 200);
    }
    public function destroy(Uom $resource)
    {
        $resource->delete();
        return response()->json([
            'message' => 'UOM deleted successfully.',
            'data' => $resource,
        ], 200);
    }
}
