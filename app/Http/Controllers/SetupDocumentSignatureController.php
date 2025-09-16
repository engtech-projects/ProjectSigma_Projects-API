<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTypeRequest;
use App\Http\Requests\StoreOrUpdateDocumentSignaturesRequest;
use App\Http\Resources\SetupDocumentSignatureResource;
use App\Models\SetupDocumentSignature;
use Illuminate\Support\Facades\DB;

class SetupDocumentSignatureController extends Controller
{
    public function index()
    {
        $signatures = SetupDocumentSignature::all()
            ->groupBy('document_type');
        $grouped = collect(SetupDocumentSignature::DOCUMENT_TYPES)
        ->mapWithKeys(function ($type) use ($signatures) {
            return [
                $type => $signatures->get($type, collect([])),
            ];
        });
        return SetupDocumentSignatureResource::collection($signatures->flatten())
            ->additional([
                'success' => true,
                'message' => 'Document signatures retrieved successfully.',
                'grouped' => $grouped
            ]);
    }
    public function showByDocumentType(DocumentTypeRequest $request)
    {
        $validated = $request->validated();
        $documentType = $validated['document_type'];
        $signatures = SetupDocumentSignature::where('document_type', $documentType)->get();
        return SetupDocumentSignatureResource::collection($signatures)
            ->additional([
                'success'       => true,
                'message'       => 'Document signature(s) retrieved successfully.',
                'document_type' => $documentType,
                'count'         => $signatures->count(),
            ]);
    }
    public function destroy(SetupDocumentSignature $documentSignature)
    {
        try {
            $documentSignature->delete();
            if ($documentSignature->trashed()) {
                return response()->json([
                    'message' => 'Signature deleted successfully.'
                ], 200);
            }
            return response()->json([
                'message' => 'Failed to delete signature.'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete signature.'
            ], 500);
        }
    }
    public function storeOrUpdate(StoreOrUpdateDocumentSignaturesRequest $request)
    {
        $validated  = $request->validated();
        DB::transaction(function () use ($validated) {
            foreach ($validated['signatures'] as $signatureData) {
                SetupDocumentSignature::updateOrCreate(
                    ['id' => $signatureData['id'] ?? null],
                    [
                        'document_type'   => $validated['document_type'],
                        'license'         => $signatureData['license'],
                        'signatory_source' => $signatureData['signatory_source'],
                        'name'            => $signatureData['name'] ?? null,
                        'user_id'     => $signatureData['user_id'] ?? null,
                        'position'        => $signatureData['position'] ?? null,
                        'signature_label'        => $signatureData['signature_label'] ?? null,
                    ]
                );
            }
        });
        return response()->json([
            'message' => 'Signatures saved successfully.',
        ], 200);
    }
}
