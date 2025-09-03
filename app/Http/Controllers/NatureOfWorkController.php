<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNatureOfWorkRequest;
use App\Http\Requests\UpdateNatureOfWorkRequest;
use App\Http\Resources\NatureOfWorkListAllResource;
use App\Http\Resources\NatureOfWorkListResource;
use App\Models\NatureOfWork;
use Illuminate\Http\JsonResponse;

class NatureOfWorkController extends Controller
{
    public function index()
    {
        $perPage = config('services.pagination.limit', 10);
        $data = NatureOfWork::latest()->paginate($perPage);
        return NatureOfWorkListResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Data fetched successfully.',
            ]);
    }
    public function all()
    {
        $data = NatureOfWork::latest()->get();
        return NatureOfWorkListAllResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Data fetched successfully.',
            ]);
    }
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
    public function destroy(NatureOfWork $nature_of_work): JsonResponse
    {
        $nature_of_work->delete();
        return response()->json([
            'message' => 'Nature of Work deleted successfully',
        ]);
    }
}
