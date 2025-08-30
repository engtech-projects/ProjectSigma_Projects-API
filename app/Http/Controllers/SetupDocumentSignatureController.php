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
}
