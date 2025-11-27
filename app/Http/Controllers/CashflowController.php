<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTssCashflowRequest;
use App\Http\Requests\UpdateCashflowItemRequest;
use App\Http\Resources\TssCashflowResource;
use App\Http\Resources\TssTaskScheduleCashflowResource;
use App\Models\Cashflow;
use App\Models\Project;
use App\Services\ProjectService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\FormatNumbers;

class CashflowController extends Controller
{
    use FormatNumbers;
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
        // Load only this project's relationships
        $project->load([
            'phases.tasks.schedules',
            'phases.tasks.resources',
        ]);
        $monthlyData = [];
        foreach ($project->phases as $part) {
            foreach ($part->tasks as $item) {
                foreach ($item->schedules as $schedule) {
                    // Prevent null-date schedules from breaking output
                    if (!$schedule->start_date || !$schedule->end_date) {
                        continue;
                    }
                    $start = Carbon::parse($schedule->start_date);
                    $end   = Carbon::parse($schedule->end_date);
                    // Iterate each overlapped month
                    $period = CarbonPeriod::create(
                        $start->copy()->startOfMonth(),
                        '1 month',
                        $end->copy()->endOfMonth()
                    );
                    foreach ($period as $monthDate) {
                        $monthName = $monthDate->format('F Y');
                        if (!isset($monthlyData[$monthName])) {
                            $monthlyData[$monthName] = [
                                'month' => $monthName,
                                'items' => [],
                                'totals' => [
                                    'total_materials' => 0,
                                    'total_equipment' => 0,
                                    'total_labor' => 0,
                                    'total_fuel' => 0,
                                    'total_overhead' => 0,
                                ],
                            ];
                        }
                        // Push item entry
                        $monthlyData[$monthName]['items'][] = [
                            'boq_item_id'    => $item->id,
                            'name'           => $item->name,
                            'total_materials' => $this->formatNumber($item->total_materials_amount),
                            'total_equipment' => $this->formatNumber($item->total_equipment_amount),
                            'total_labor'    => $this->formatNumber($item->total_labor_amount),
                            'total_fuel'     => $this->formatNumber($item->total_fuel_oil_amount),
                            'total_overhead' => $this->formatNumber($item->total_overhead_amount),
                        ];
                        // Accumulate totals
                        $monthlyData[$monthName]['totals']['total_materials'] += $item->total_materials_amount;
                        $monthlyData[$monthName]['totals']['total_equipment'] += $item->total_equipment_amount;
                        $monthlyData[$monthName]['totals']['total_labor'] += $item->total_labor_amount;
                        $monthlyData[$monthName]['totals']['total_fuel'] += $item->total_fuel_oil_amount;
                        $monthlyData[$monthName]['totals']['total_overhead'] += $item->total_overhead_amount;
                    }
                }
            }
        }
        return TssTaskScheduleCashflowResource::collection(array_values($monthlyData))
            ->additional([
                'success' => true,
                'message' => 'Cashflows retrieved successfully',
            ]);
    }
}
