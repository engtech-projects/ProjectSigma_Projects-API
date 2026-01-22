<?php
namespace App\Http\Controllers;
use App\Http\Requests\AccomplishmentReportRequest;
use App\Http\Requests\ProjectReportUpdateRequest;
use App\Http\Requests\StewaReportRequest;
use App\Http\Resources\ProjectReportUpdateResource;
use App\Models\Project;
use App\Services\ProjectsReportsService;
use Illuminate\Http\Request;
class ProjectsReportsController extends Controller
{
    //
    protected ProjectsReportsService $projectReportService;
    public function __construct(ProjectsReportsService $projectReportService)
    {
        $this->projectReportService = $projectReportService;
    }
    public function getProjectReportUpdate(ProjectReportUpdateRequest $request)
    {
        $validated = $request->validated();
        $result = $this->projectReportService->fetchProjectUpdateReport(
            $validated['dateFrom'],
            $validated['dateTo'],
        );
        return ProjectReportUpdateResource::collection($result['projects'])
            ->additional([
                'message' => 'Project report loaded successfully',
                'status' => 'success',
            ]);
    }
    public function getStewaReport(StewaReportRequest $request)
    {
    }
    public function getAccomplishmentReport(AccomplishmentReportRequest $request)
    {
    }
}
