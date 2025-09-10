<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateDocumentSignaturesRequest;
use App\Http\Requests\StoreSetupDocumentSignatureRequest;
use App\Http\Requests\UpdateSetupDocumentSignatureRequest;
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
    public function store(StoreSetupDocumentSignatureRequest $request)
    {
        $signature = SetupDocumentSignature::create($request->validated());
        return SetupDocumentSignatureResource::make($signature)
            ->additional([
                'success' => true,
                'message' => 'Document signature created successfully.',
            ])
            ->response();
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
    public function update(UpdateSetupDocumentSignatureRequest $request, SetupDocumentSignature $setupDocumentSignature)
    {
        $setupDocumentSignature->update($request->validated());
        return SetupDocumentSignatureResource::make($setupDocumentSignature)
            ->additional([
                'success' => true,
                'message' => 'Document signature updated successfully.',
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
