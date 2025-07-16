<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Traits\Upload;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectAttachmentController extends Controller
{
    use Upload;

    /**
     * Handles uploading and storing multiple attachments for a project.
     *
     * Validates the incoming request, uploads each provided file to a project-specific directory, and creates corresponding attachment records linked to the project. Returns a JSON response containing all attachments for the project.
     *
     * @return \Illuminate\Http\JsonResponse List of the project's attachments with HTTP status 201.
     */
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

    public function generateUrl(Request $request, Project $project)
    {
        $attachments = $project->attachments()->get();

        if ($attachments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No attachments found',
                'data' => [],
            ], 404);
        }

        $uniqueKey = Str::random(15);

        Cache::put($uniqueKey, $project->id, now()->addMinutes(10));

        $webViewerUrl = route('web.document.viewer', ['cacheKey' => $uniqueKey]);

        return response()->json([
            'success' => true,
            'message' => 'Document viewer link generated successfully.',
            'data' => ['url' => $webViewerUrl],
        ], 200);
    }

    /****
     * Deletes the specified attachment and its associated file from storage.
     *
     * Removes the physical file from storage using the attachment's path, deletes the attachment record from the database, and returns a JSON response indicating successful deletion.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with the string 'deleted' and HTTP status 200.
     */
    public function destroy(Request $request, Attachment $attachment)
    {
        $this->deleteFile($attachment->path);
        $attachment->delete();

        return response()->json('deleted', 200);
    }
}
