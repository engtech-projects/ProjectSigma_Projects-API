<?php

namespace App\Services;

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

    public function getProjects()
    {
        $projects = $this->project->paginate();
        return $projects;
    }
    public function createProject(array $data)
    {
        DB::beginTransaction();
        try {
            $project = $this->project->create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage(), $e->getCode());
        }
        return $project;
    }

    public function updateProject(array $data, Project $project)
    {
        DB::beginTransaction();
        try {
            $project = $project->fill($data);
            $project->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage(), $e->getCode());
        }
        return $project;
    }

    public function deleteProject(Project $project)
    {
        DB::beginTransaction();
        try {
            $project->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage(), $e->getCode());
        }
        return $project;
    }

}
