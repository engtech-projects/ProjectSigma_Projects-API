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
use Illuminate\Http\JsonResponse;
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

    /**
     * Handles the upload of multiple attachment files to a temporary directory.
     *
     * Validates that the request contains an array of files under 'attachment_files', stores each file in the 'temp/' directory with a randomly generated encrypted filename, and returns a JSON response with the list of encrypted filenames.
     *
     * @return JsonResponse JSON response containing success status, message, and the list of encrypted filenames.
     */
    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'attachment_files' => 'required|array',
            'attachment_files.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $encryptedFileNames = [];

        try {
            if ($request->hasFile('attachment_files')) {
                foreach ($request->file('attachment_files') as $file) {
                    $encryptedFileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('temp/', $encryptedFileName);
                    $encryptedFileNames[] = $encryptedFileName;
                }
            }
        } catch (\Exception $e) {
            // Clean up any partially uploaded files
            foreach ($encryptedFileNames as $filename) {
                Storage::delete('temp/' . $filename);
            }
            throw $e;
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Attachments uploaded successfully',
            'data' => $encryptedFileNames,
        ], 200);

    }

    public function generateUrl(Request $request, Project $project)
    {
        $attachments = $project->attachments()->get();

        if (empty($attachments) || count($attachments) === 0) {
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
            'data' => ['url'=> $webViewerUrl],
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
