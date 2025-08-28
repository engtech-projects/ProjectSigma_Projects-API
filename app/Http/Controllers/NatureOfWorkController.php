<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNatureOfWorkRequest;
use App\Http\Requests\UpdateNatureOfWorkRequest;
use App\Models\NatureOfWork;
use Illuminate\Http\JsonResponse;

class NatureOfWorkController extends Controller
{
    public function store(StoreNatureOfWorkRequest $request): JsonResponse
    {
        $natureOfWork = NatureOfWork::create($request->validated());

        return response()->json([
            'message' => 'Nature of Work created successfully',
            'data' => $natureOfWork,
        ], 201);
    }

    public function update(UpdateNatureOfWorkRequest $request, NatureOfWork $nature_of_work): JsonResponse
    {
        $nature_of_work->update($request->validated());

        return response()->json([
            'message' => 'Nature of Work updated successfully',
            'data' => $nature_of_work,
        ]);
    }

    // Delete (soft delete)
    public function destroy(NatureOfWork $nature_of_work): JsonResponse
    {
        $nature_of_work->delete();

        return response()->json([
            'message' => 'Nature of Work deleted successfully',
        ]);
    }
}
