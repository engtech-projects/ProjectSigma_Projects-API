<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTssCashflowRequest;
use App\Http\Requests\UpdateCashflowItemRequest;
use App\Http\Resources\TssCashflowResource;
use App\Http\Resources\TssTaskScheduleCashflowResource;
use App\Models\Cashflow;
use App\Models\Project;
use App\Services\ProjectService;

class CashflowController extends Controller
{
    public function index(Project $project)
    {
        return TssCashflowResource::collection($project->cashflows()->with('cashflowItems.item')->get())
            ->additional([
                'success' => true,
                'message' => 'Cashflows retrieved successfully',
            ]);
    }

    public function store(Project $project, StoreTssCashflowRequest $request)
    {
        $projectService = new ProjectService($project);
        $cashflow = $projectService->storeCashflow($request->validated());
        $cashflow->load('cashflowItems.item');
        return TssCashflowResource::make($cashflow)
            ->additional([
                'success' => true,
                'message' => 'Cashflow created successfully',
            ]);
    }

    public function update(Project $project, Cashflow $cashflow, UpdateCashflowItemRequest $request)
    {
        if ($cashflow->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'The specified cashflow does not belong to the given project.',
            ], 400);
        }
        $cashflow->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Cashflow updated successfully',
        ], 200);
    }

    public function restore(Project $project, $cashflowId)
    {
        $deletedCashflow = $project->cashflows()->withTrashed()->findOrFail($cashflowId);
        $deletedCashflow->restore();
        return response()->json([
            'success' => true,
            'message' => 'Cashflow restored successfully',
        ], 200);
    }

    public function getTasksSchedulesCashflows(Project $project)
    {
        return TssTaskScheduleCashflowResource::collection($project->taskSchedules()->get())
            ->additional([
                'success' => true,
                'message' => 'Cashflows retrieved successfully',
            ]);
    }
}
