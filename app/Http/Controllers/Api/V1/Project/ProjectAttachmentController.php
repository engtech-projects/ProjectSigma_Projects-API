<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Attachment;
use App\Traits\Upload;

class ProjectAttachmentController extends Controller
{
    use Upload;

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'attachments' => ['required', 'min:1', 'array'],
            'attachments.*' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,png,docx,doc,csv,xlsx,xls,ppt'],
        ]);

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

    // public function download() {}

}
