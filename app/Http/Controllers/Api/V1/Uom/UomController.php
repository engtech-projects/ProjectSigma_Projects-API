<?php

namespace App\Http\Controllers\Api\V1\Uom;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterUomRequest;
use App\Http\Requests\StoreUomRequest;
use App\Http\Requests\UpdateUomRequest;
use App\Models\Uom;
use App\Services\UomService;

class UomController extends Controller
{
    public function index(FilterUomRequest $request)
    {
        $validatedData = $request->validated();

        return response()->json([
            'message' => 'Units fetched successfully.',
            'data' => UomService::withPaginate($validatedData),
        ], 200);
    }

    public function all()
    {
        return response()->json([
            'message' => 'Units fetched successfully.',
            'data' => UomService::all(),
        ], 200);
    }

    public function store(StoreUomRequest $request)
    {
        return response()->json([
            'message' => 'UOM added successfully.',
            'data' => Uom::create($request->validated()),
        ], 201);
    }

    public function update(UpdateUomRequest $request, Uom $uom)
    {
        $validatedRequest = $request->validated();
        $uom->update($validatedRequest);

        return response()->json([
            'message' => 'UOM updated successfully.',
            'data' => $uom,
        ], 200);
    }

    public function destroy(Uom $uom)
    {
        $uom->delete();

        return response()->json([
            'message' => 'UOM deleted successfully.',
            'data' => $uom,
        ], 200);
    }
}
