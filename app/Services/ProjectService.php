<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Exceptions\DBTransactionException;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

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
    public function createProject(array $data)
    {
        try {
            return $this->project->create($data);
        } catch (Exception $e) {
            throw new DBTransactionException("Transaction failed.", 500, $e);
        }
    }

    public function updateProject(array $data, Project $project)
    {
        try {
            return $project->fill($data)->save();
        } catch (Exception $e) {
            throw new DBTransactionException("Transaction failed.", 500, $e);
        }
    }

    public function deleteProject(Project $project)
    {
        try {
            return $project->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Transaction failed.", 500, $e);
        }
    }

}
