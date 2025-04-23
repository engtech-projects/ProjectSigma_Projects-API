<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Traits\Upload;
use Illuminate\Http\Request;

class ProjectAttachmentController extends Controller
{
    use Upload;

    public function store(StoreAttachmentRequest $request, Project $project)
    {
        $validated = $request->validated();

        foreach ($request->file('attachments') as $attachment) {

            $path = $this->uploadFile($attachment, "projects/{$project->id}");

            $project->attachments()->create([
                'name' => $attachment->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $attachment->getMimeType(),
            ]);
        }

        return response()->json($project->attachments()->get(), 201);
    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $this->deleteFile($attachment->path);
        $attachment->delete();

        return response()->json('deleted', 200);
    }
}
