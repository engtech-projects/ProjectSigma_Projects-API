<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectCashFlowRequest;
use App\Http\Requests\UpdateCashflowItemRequest;
use App\Http\Resources\ProjectCashFlowResource;
use App\Http\Resources\UpdateCashflowResource;
use App\Models\Cashflow;
use App\Models\Project;
use App\Services\ProjectService;

class CashflowController extends Controller
{
    public function index(Project $project)
    {
        return ProjectCashflowResource::collection($project->cashflows()->with('cashflowItems.item')->get())
            ->additional([
                'success' => true,
                'message' => 'Cashflows retrieved successfully',
            ]);
    }

    public function store(Project $project, StoreProjectCashFlowRequest $request)
    {
        $projectService = new ProjectService($project);
        $cashflow = $projectService->storeCashflow($request->validated());
        $cashflow->load('cashflowItems.item');
        return ProjectCashflowResource::make($cashflow)
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
        return UpdateCashflowResource::make($cashflow)
            ->additional([
                'success' => true,
                'message' => 'Cashflow updated successfully',
            ]);
    }
}
