<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReplicateProject extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        if( !$project->isOriginal() )
        {
            return response()->json([
                'error' => 'Cannot replicate.',
            ]);
        }

        $replica = $project->replicate();

        return response()->json([
            'message' => 'Project cloned successfully.',
            'replica' => $replica,
        ]);

    }
}
