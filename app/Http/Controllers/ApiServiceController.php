<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectDetailedEnumResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ApiServiceController extends Controller
{
    public function getProjectList()
    {
        $projectList = Project::orderBy('code')->awarded()->get();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully fetched.',
            'data' => ProjectDetailedEnumResource::collection($projectList),
        ]);
    }
}
