<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectWithDeletedResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ApiServiceController extends Controller
{
    public function getProjectList()
    {
        $projectList = Project::withTrashed()
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->awarded()
            ->orderBy('code')
            ->get();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully fetched.',
            'data' => ProjectWithDeletedResource::collection($projectList),
        ]);
    }
}
