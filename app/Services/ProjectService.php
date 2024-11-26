<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Exceptions\DBTransactionException;
use App\Models\Project;
use App\Models\Phase;
use Carbon\Carbon;
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

	public function create(array $attr)
	{
		try {
			return DB::transaction(function () use ($attr) {
				return Project::create($attr);
			});
		} catch (\Throwable $e) {
			// Return response
			return ['error' => $e->getMessage()];
		}
	}

	public function update(Project $project, array $attr)
	{
		try {
		
			DB::transaction(function () use ($project, $attr) {
				$project->fill($attr)->save();
			});
	
			return $project;
		} catch (\Throwable $e) {
			// Log the exception for debugging purposes
			Log::error('Project Update Error: ' . $e->getMessage(), ['exception' => $e]);
			// Return response
			return ['error' => $e->getMessage()];
		}
	}

	public function addPhases(Project $project, array $attr)
	{
		try {
		
			DB::transaction(function () use ($project, $attr) {
				foreach ($attr as $phase) {
					$project->phases()->updateOrCreate(
						['id' => $phase['id'] ?? null], // Match id
						$phase // Data to update or create
					);
				}
			});
			
			return $project->phases()->get();
		} catch (\Throwable $e) {
			// Return response
			return ['error' => $e->getMessage()];
		}
	}

	public function addTasks(Phase $phase, array $attr)
	{
		try {
		
			DB::transaction(function () use ($phase, $attr) {
				foreach ($attr as $task) {
					$phase->tasks()->updateOrCreate(
						['id' => $task['id'] ?? null], // Match id
						$task // Data to update or create
					);
				}
			});
	
			return $phase->tasks()->get();
		} catch (\Throwable $e) {
			// Return response
			return ['error' => $e->getMessage()];
		}
	}

	public function addResources(Project $project, array $attr)
	{
		// try {
		
		// 	DB::transaction(function () use ($project, $attr) {
		// 		$project->phases()->createMany($attr);
		// 	});
	
		// 	return $project->phases();
		// } catch (\Throwable $e) {
		// 	// Return response
		// 	return ['error' => $e->getMessage()];
		// }
	}
}
