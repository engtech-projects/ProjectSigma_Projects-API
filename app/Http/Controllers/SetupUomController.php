<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetupUomRequest;
use App\Http\Requests\UpdateSetupUomRequest;
use App\Http\Resources\SetupUomResource;
use App\Models\SetupUom;
use Illuminate\Http\Request;

class SetupUomController extends Controller
{
    protected SetupUom $setupUom;

    public function __construct(SetupUom $setupUom)
    {
        $this->setupUom = $setupUom;
    }

    public function index(SetupUom $setupUom)
    {
        return SetupUomResource::collection($setupUom->get())
            ->additional([ 
                'success' => true,
                'message' => 'All SetupUom fetched successfully',
            ]);
    }

    public function store(SetupUom $setupUom, StoreSetupUomRequest $request)
    {
        $createSetupUom = $setupUom->create($request->validated());
        return SetupUomResource::make($createSetupUom)
            ->additional([
                'success' => true,
                'message' => 'Setup Uom created successfully',
            ]);
    }

    public function show(SetupUom $setupUom)
    {
        return SetupUomResource::make($setupUom)
            ->additional([
                'success' => true,
                'message' => 'Setup Uom fetched successfully'
            ]);
    }

    public function update(SetupUom $setupUom, UpdateSetupUomRequest $request)
    {
        $setupUom->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Setup Uom updated successfully',
        ]);
    }

    public function destroy(SetupUom $setupUom)
    {
        $setupUom->delete();
         return response()->json([
            'success' => true,
            'message' => 'Setup Uom deleted successfully',
        ]);
    }
}
