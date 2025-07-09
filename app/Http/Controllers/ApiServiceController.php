<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStage;
use App\Enums\TssStage;
use App\Http\Resources\ProjectDetailedEnumResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ApiServiceController extends Controller
{
    public function getOldProjectList()
    {
        $projectList = Project::orderBy('code')->where('stage', ProjectStage::AWARDED)->get();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully fetched.',
            'data' => ProjectDetailedEnumResource::collection($projectList),
        ]);
    }

    public function getProjectList()
    {
        $projectList = Project::orderBy('code')
            ->where(function ($q) {
                $q->where('tss_stage', '!=', TssStage::Pending->value)
                ->where('tss_stage', ProjectStage::AWARDED->value);
            })->orWhere(function ($q) {
                $q->where('tss_stage', TssStage::Pending->value)
                ->where('marketing_stage', ProjectStage::AWARDED->value);
            })
            ->get();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully fetched.',
            'data' => ProjectDetailedEnumResource::collection($projectList),
        ]);
    }
}
