<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectCashFlowRequest;
use App\Http\Resources\ProjectCashFlowResource;
use App\Models\Cashflow;
use App\Models\Project;
use App\Services\CashflowService;
use App\Services\ProjectService;

class CashflowController extends Controller
{

    public function showProjectCashflows(Project $project_id)
    {
        $data = $project_id->cashflow()->with('cashflow_items')->get();
        return ProjectCashFlowResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Cashflows retrieved successfully',
            ]);
    }

    public function storeProjectCashflow(Project $project_id, StoreProjectCashFlowRequest $request)
    {
        $projectService = new ProjectService($project_id);
        $cashflow = $projectService->createCashflow($request->validated());
        return ProjectCashflowResource::make($cashflow)
            ->additional([
                'success' => true,
                'message' => 'Cashflow retrieved successfully',
            ]);
    }

    public function updateProjectCashflow(Project $project_id, Cashflow $cashflow_id)
    {
        $data = $project_id->cashflow($cashflow_id)->update();
        return ProjectCashFlowResource::make($data)
            ->additional([
                'success' => true,
                'message' => 'Cashflow updated successfully',
            ]);
    }
}
