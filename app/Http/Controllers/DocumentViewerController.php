<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class DocumentViewerController extends Controller
{
    /**
     * Handles HTTP requests to display project document attachments.
     *
     * Validates the request for a valid project ID, retrieves the corresponding project, and processes its attachments. Copies attachment files to a public directory if necessary and returns a view with the list of public file paths. If the project or attachments are not found, or an error occurs, returns a JSON response with a 404 status and error message.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse The document viewer view with attachment paths, or a JSON error response if attachments are not found.
     */
    public function __invoke(Request $request)
    {
        $validatedRequest = $request->validate([
            'id' => 'required|integer|exists:projects,id',
        ]);

        $prfId = $validatedRequest['id'];

        try {
            $prf = Project::where('id', $prfId)->first();

            if ($prf && !empty($prf->attachment_url)) {
                $attachmentUrls = is_array($prf->attachment_url) ? $prf->attachment_url : json_decode($prf->attachment_url, true);
                $publicFilePaths = [];

                foreach ($attachmentUrls as $attachmentUrl) {
                    $originalFilePath = "prf/$prfId/$attachmentUrl";
                    $publicFilePath = "storage/prf/$prfId/$attachmentUrl";
                    $publicDir = public_path("storage/prf/$prfId");

                    if (!file_exists($publicDir)) {
                        mkdir($publicDir, 0777, true);
                    }
                    if (!file_exists(public_path($publicFilePath))) {
                        copy(storage_path("app/$originalFilePath"), public_path($publicFilePath));
                    }

                    $publicFilePaths[] = $publicFilePath;
                }

                return view('document-viewer', [
                    'title' => 'Sigma Projects Attachments',
                    'publicFilePaths' => $publicFilePaths,
                ]);
            }

            throw new \Exception('Attachments Not Found');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 404);
        }
    }
}
