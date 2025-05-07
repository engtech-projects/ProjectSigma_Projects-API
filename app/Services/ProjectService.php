<?php

namespace App\Services;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Resources\Project\ProjectCollection;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function create(array $attr)
    {
        return DB::transaction(function () use ($attr) {
            $attr['stage'] = ProjectStage::DRAFT->value;
            $attr['created_by'] = auth()->user()->id;
            $attr['cash_flow'] = json_encode(array_fill_keys(['wtax', 'q1', 'q2', 'q3', 'q4'], [
                'accomplishment' => 0,
                'cashflow' => 0,
                'culmutative_accomplishment' => 0,
                'culmutative_cashflow' => 0,
            ]));
            $data = Project::create($attr);

            return new JsonResponse([
                'message' => 'Project created successfully.',
                'data' => $data,
            ], 201);
        });
    }

    public function changeSummaryRates(array $attr)
    {
        return DB::transaction(function () use ($attr) {
            DB::table('resources')
                ->whereIn('id', $attr['ids'])
                ->update(['unit_cost' => $attr['unit_cost']]);

            return new JsonResponse([
                'message' => 'Summary rates updated successfully, Number of Direct Cost Affected: ' . count($attr['ids']),
            ], 200);
        });
    }

    public function withPagination(array $attr)
    {
        $query = Project::query();

        $query->when(isset($attr['key']), function ($query) use ($attr) {
            $query->where('name', 'like', "%{$attr['key']}%")
                ->orWhere('code', 'like', "%{$attr['key']}%");
        });
        $query->when(isset($attr['status']), function ($query) use ($attr) {
            if ($attr['status'] === ProjectStatus::DRAFT->value) {
                $query->where('created_by', auth()->user()->id);
            }
            $query->where('stage', $attr['status']);
        });

        return $query->paginate(config('services.pagination.limit'));
    }

    public function update(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            $project->fill($attr)->save();

            return new JsonResponse([
                'message' => 'Project updated successfully.',
                'data' => $project,
            ], 200);
        });
    }

    public function assignTeam(Project $project, array $attr)
    {
        return DB::transaction(function () use ($project, $attr) {
            foreach ($attr as $personnel) {
                $personnel['project_id'] = $project->id;
                $project->team()->updateOrCreate(
                    ['id' => $personnel['id'] ?? null],
                    $personnel
                );
            }

            return new JsonResponse([
                'message' => 'Team assigned successfully.',
                'data' => $project->team()->get(),
            ], 200);
        });
    }
}
