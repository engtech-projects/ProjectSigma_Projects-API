<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTypeRequest;
use App\Http\Requests\StoreOrUpdateDocumentSignaturesRequest;
use App\Http\Resources\SetupDocumentSignatureResource;
use App\Models\SetupDocumentSignature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetupDocumentSignatureController extends Controller
{
    public function index()
    {
        $signatures = SetupDocumentSignature::all()
            ->groupBy('document_type');
        return SetupDocumentSignatureResource::collection($signatures->flatten())
            ->additional([
                'success' => true,
                'message' => 'Document signatures retrieved successfully.',
                'grouped' => $signatures
            ]);
    }
    public function show(SetupDocumentSignature $setupDocumentSignature)
    {
        return SetupDocumentSignatureResource::make($setupDocumentSignature)
            ->additional([
                'success' => true,
                'message' => 'Document signature retrieved successfully.',
            ])
            ->response();
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
    public function destroy(SetupDocumentSignature $setupDocumentSignature)
    {
        $setupDocumentSignature->delete();
        return response()->json(['message' => 'Signature soft deleted']);
    }
    public function storeOrUpdate(StoreOrUpdateDocumentSignaturesRequest $request)
    {
        $validated  = $request->validated();
        DB::beginTransaction();
        try {
            foreach ($validated['signatures'] as $signatureData) {
                Log::info("Processing signature data: " . $signatureData['signatory_source']);
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
            DB::commit();
            return response()->json([
                'message' => 'Signatures saved successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to save signatures.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
