<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Auth;
use Cache;
use File;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Str;

class DocumentViewerController extends Controller
{
    /**
     * Handles HTTP requests to display project document attachments.
     *
     * Validates the request for a valid project ID, retrieves the corresponding project, and processes its attachments. Copies attachment files to a public directory if necessary and returns a view with the list of public file paths. If the project or attachments are not found, or an error occurs, returns a JSON response with a 404 status and error message.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse The document viewer view with attachment paths, or a JSON error response if attachments are not found.
     */
    public function __invoke($cacheKey)
    {
        if(!Cache::has($cacheKey)) {
            return view('document-not-found', ['message' => 'Document not found or cache key has expired.']);
        }

        $projectId = Cache::get($cacheKey);
        $project = Project::find($projectId);

        if (!$project || empty($project->attachments) || !count($project->attachments)) {
            return view('document-not-found', ['message' => 'Project not found or attachments not found.']);
        }

        $publicFilePaths = [];

        foreach ($project->attachments as $attachment) {
            $originalFilePath = "project/{$project->id}/$attachment";
            $publicFilePath = "storage/project/{$project->id}/$attachment";
            $publicDir = public_path("storage/project/{$project->id}");

            if (!file_exists($publicDir)) {
                if (!mkdir($publicDir, 0755, true)) {
                    throw new \Exception('Failed to create directory');
                }
            }

            if (!file_exists($publicFilePath)) {
                if (!copy(storage_path("app/{$originalFilePath}"), $publicFilePath)) {
                    throw new \Exception('Failed to copy file');
                }
            }

            $publicFilePaths[] = $publicFilePath;
        }

        return view('document-viewer', [
            'title' => 'Sigma Projects Attachments',
            'publicFilePaths' => $publicFilePaths,
        ]);
    }

}
