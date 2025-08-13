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
        try {
            if (!$request->hasFile('attachments')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files uploaded',
                ], 422);
            }
            $storedFiles = [];
            foreach ($request->file('attachments') as $file) {
                $uniqueName = (string) Str::uuid(). '.' . $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $path = "project/attachments/{$project->id}/{$uniqueName}";
                Storage::disk('public')->put($path, file_get_contents($file));
                $attachment = $project->attachments()->create([
                    'project_id' => $project->id,
                    'name' => $originalName,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    ]);
                $storedFiles[] = $attachment;
            }
            return response()->json([
                'success' => true,
                'message' => 'Files uploaded and stored successfully.',
                'files' => $storedFiles,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload attachment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getDocumentViewerLink(Request $request, Project $project)
    {
        $uniqueKey = Str::random(15);
        Cache::put($uniqueKey, $project->id, now()->addMinutes(10));
        $webViewerUrl = route('web.document.viewer', ['cacheKey' => $uniqueKey]);
        return response($webViewerUrl, 200)
            ->header('Content-Type', 'text/plain');
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
