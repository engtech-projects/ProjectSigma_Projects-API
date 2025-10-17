<?php

namespace App\Http\Controllers;

use Cache;
use File;
use Illuminate\Http\Request;
use App\Models\Project;
use Storage;

class DocumentViewerController extends Controller
{
    /**
     * Handles HTTP requests to display project document attachments.
     *
     * Validates the request for a valid project ID, retrieves the corresponding project, and processes its attachments. Copies attachment files to a public directory if necessary and returns a view with the list of public file paths. If the project or attachments are not found, or an error occurs, returns a JSON response with a 404 status and error message.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse The document viewer view with attachment paths, or a JSON error response if attachments are not found.
     */
    public function showDocumentViewer($cacheKey)
    {
        if (!Cache::has($cacheKey)) {
            return view('errors.document-not-found', [
                'message' => 'Document not found or cache key expired.'
            ]);
        }
        $projectId = Cache::get($cacheKey);
        $project = Project::find($projectId);
        if (!$project) {
            return view('errors.document-not-found', [
                'message' => 'Project not found.'
            ]);
        }
        $attachments = $project->attachments()->get();
        if ($attachments->isEmpty()) {
            return view('errors.document-not-found', [
                'message' => 'No attachments found for this project.'
            ]);
        }
        $files = $attachments->map(function ($attachment) {
            $relativePath = "project/attachments/{$attachment->project_id}/{$attachment->name}";
            $fullPath = storage_path('app/public/' . $attachment->path);
            return file_exists($fullPath) ? [
                'name' => $attachment->name,
                'mime_type' => $attachment->mime_type,
                'url' => Storage::url($relativePath),
                'path' => $relativePath,
            ] : null;
        })->filter()->values();
        if ($files->isEmpty()) {
            return view('errors.document-not-found', [
                'message' => 'All attachments are missing from storage.'
            ]);
        }
        return view('document-viewer', [
            'project' => $project,
            'files' => $files,
        ]);
    }

    public function download($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }
        return Storage::disk('public')->download($path);
    }
}
