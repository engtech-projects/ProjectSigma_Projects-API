<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

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

    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'attachment_files' => 'required|array',
            'attachment_files.*' => 'file',
        ]);

        $encryptedFileNames = [];

        if ($request->hasFile('attachment_files')) {
            foreach ($request->file('attachment_files') as $file) {
                $encryptedFileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('temp/', $encryptedFileName);
                $encryptedFileNames[] = $encryptedFileName;
            }
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Attachments uploaded successfully',
            'data' => $encryptedFileNames,
        ], 200);

    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $this->deleteFile($attachment->path);
        $attachment->delete();

        return response()->json('deleted', 200);
    }
}
