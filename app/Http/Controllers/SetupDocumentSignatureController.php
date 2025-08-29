<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetupDocumentSignatureRequest;
use App\Http\Requests\UpdateSetupDocumentSignatureRequest;
use App\Http\Resources\SetupDocumentSignatureResource;
use App\Models\SetupDocumentSignature;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $signature = SetupDocumentSignature::create($request->validated());
        return (new SetupDocumentSignatureResource($signature))
            ->additional([
                'success' => true,
                'message' => 'Document signature created successfully.',
            ])
            ->response()
            ->setStatusCode(201);
    }
    public function show(StoreSetupDocumentSignatureRequest $setupDocumentSignature)
    {
        return (new SetupDocumentSignatureResource($setupDocumentSignature))
            ->additional([
                'success' => true,
                'message' => 'Document signature retrieved successfully.',
            ]);
    }
    public function update(UpdateSetupDocumentSignatureRequest $request, SetupDocumentSignature $setupDocumentSignature)
    {
        $setupDocumentSignature->update($request->validated());
        return (new SetupDocumentSignatureResource($setupDocumentSignature))
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
}
