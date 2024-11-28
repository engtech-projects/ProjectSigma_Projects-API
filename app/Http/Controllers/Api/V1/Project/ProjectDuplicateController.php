<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectDuplicateController extends Controller
{
    public function clone(Project $project)
    {

        if( !$project->isOriginal() )
        {
            return response()->json([
                'error' => 'Cannot clone project.',
            ]);
        }

        $clonedProject = $project->replicate();

        return response()->json([
            'message' => 'Project cloned successfully.',
            'cloned_project' => $clonedProject,
        ]);
    }

    // protected function cloneRelationships(Project $project, Project $clonedProject, array $relationships)
    // {
    //     foreach ($relationships as $relation) {
    //         foreach ($project->$relation as $related) {
    //             $clonedRelated = $related->replicate();
    //             $clonedRelated->project_id = $clonedProject->id; // Update foreign key
    //             $clonedRelated->save();
    //         }
    //     }
    // }

}
