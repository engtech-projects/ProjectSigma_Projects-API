<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
        $url = URL::temporarySignedRoute('document.viewer', now()->addMinutes(3), ['id' => $request->id]);

        $validatedRequest = $request->validate([
            'id' => 'required|integer|exists:projects,id',
        ]);

        $prfId = $validatedRequest['id'];

        try {
            $prf = Project::find($prfId);

            if ($prf && !empty($prf->attachment_url)) {
                $attachmentUrls = is_array($prf->attachment_url) ? $prf->attachment_url : json_decode($prf->attachment_url, true);
                $publicFilePaths = [];

                foreach ($attachmentUrls as $attachmentUrl) {
                    // Validate filename to prevent path traversal attacks
                    if (strpos($attachmentUrl, '..') !== false || strpos($attachmentUrl, './') !== false) {
                        continue; // Skip potentially malicious file paths
                    }
                    $originalFilePath = "prf/$prfId/$attachmentUrl";
                    $publicFilePath = "storage/prf/$prfId/$attachmentUrl";
                    $publicDir = public_path("storage/prf/$prfId");

                    if (!file_exists($publicDir)) {
                        if (!mkdir($publicDir, 0755, true)) {
                            throw new \Exception('Failed to create directory');
                        }
                    }
                    $sourceFile = storage_path("app/$originalFilePath");
                    if (!file_exists($sourceFile)) {
                        continue;// skip if file does not exist
                    }

                    if (!file_exists(public_path($publicFilePath))) {
                        if (!copy($sourceFile, public_path($publicFilePath))) {
                            throw new \Exception('Failed to copy file');
                        }
                    }

                    $publicFilePaths[] = $publicFilePath;
                }

                return view('document-viewer', [
                    'title' => 'Sigma Projects Attachments',
                    'publicFilePaths' => $publicFilePaths,
                    'url' => $url,
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
