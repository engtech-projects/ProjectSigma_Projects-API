<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReplicateProject extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        if ($project->isOriginal() && $project->isApproved()) {
            $replica = $project->replicate();
            $replica->uuid = (string) Str::uuid();
            $replica->name = $project->name.'(Revised)';
            $replica->code = $this->generateUniqueCode($project->contract_id);
            $replica->version = (int) $project->version + 1;
            $replica->status = ProjectStatus::OPEN;
            $replica->stage = ProjectStage::AWARDED;
            $replica->is_original = false;
            $replica->parent_project_id = $project->id;
            $replica->save();

            // foreach (['phases'] as $relation) {
            //     if ($project->$relation()->exists()) {
            //         foreach ($project->$relation as $related) {
            //             // return response()->json([
            //             //     'message' => 'Awarded Project can be viewed in ******.',
            //             //     'replica' => new ProjectResource($replica),
            //             // ]);
            //             // $clonedRelated = $related->replicate();
            //             // $clonedRelated->project_id = $replica->id; // Update foreign key
            //             // $clonedRelated->save();
            //         }
            //     }
            // }
            return response()->json([
                'message' => 'Awarded Project can be viewed in ******.',
                'replica' => new ProjectResource($replica),
            ]);
        }

        // $replica->toJson()
        return response()->json([
            'error' => 'Cannot replicate.',
        ]);
    }

    protected function generateUniqueCode(string $baseCode): string
    {
        do {
            // Generate 4 random uppercase characters
            $randomString = Str::upper(Str::random(4));
            $newCode = $baseCode.$randomString;

            // Check if the code already exists in the database
            $exists = Project::where('code', $newCode)->exists();
        } while ($exists);

        return $newCode;
    }
}
