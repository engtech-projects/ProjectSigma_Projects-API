<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStage;
use App\Http\Resources\ProjectDetailedEnumResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ApiServiceController extends Controller
{
    public function getEmployeeList()
    {
        $employeeList = Project::orderBy('code')->where('stage', ProjectStage::AWARDED)->get();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully fetched.',
            'data' => ProjectDetailedEnumResource::collection($employeeList),
        ]);
    }
}
