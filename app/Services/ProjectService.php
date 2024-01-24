<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    protected $project;
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProjects(string $status = null)
    {
        switch ($status) {
            case null:
                return $this->getProjectsPaginated($status);
            case ProjectStatus::COMPLETED->value:
                return $this->getProjectsPaginated($status);
            case ProjectStatus::ONGOING->value:
                return $this->getProjectByStatus($status);
            default;
        }

    }

    public function getProjectsPaginated(string $status = null)
    {
        if ($status === ProjectStatus::COMPLETED->value) {
            return $this->project->byProjectStatus($status)->paginate();
        }
        return $this->project->paginate();

    }
    public function getProjectByStatus($status)
    {
        return $this->project->byProjectStatus($status)->get();
    }
    public function createProject(array $data): Project
    {
        DB::beginTransaction();
        try {
            $project = $this->project->create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $project;
    }

    public function updateProject(array $data, Project $project): Project
    {
        DB::beginTransaction();
        try {
            $project = $project->fill($data);
            $project->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $project;
    }

    public function deleteProject(Project $project): string
    {
        DB::beginTransaction();
        try {
            $project->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return "Project deleted.";
    }

}
