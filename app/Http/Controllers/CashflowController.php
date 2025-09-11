<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCashFlowResource;
use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    protected $cashflow;

    public function __construct()
    {
        $this->cashflow = Cashflow::class;
    }

    public function showProjectCashFlows(Project $project_id)
    {
        $data = $project_id->cashflow()->get();
        return ProjectCashFlowResource::collection($data)
            ->additional([
                'success' => true,
                'message' => 'Activities retrieved successfully',
            ]);
    }
}
