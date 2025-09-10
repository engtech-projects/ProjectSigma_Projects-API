<?php
namespace App\Http\Requests;
use App\Models\SetupDocumentSignature;
use Illuminate\Foundation\Http\FormRequest;
class DocumentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type' => 'required|string|in:' . implode(',', SetupDocumentSignature::DOCUMENT_TYPES),
        ];
    }
    public function messages(): array
    {
        return [
            'document_type.required' => 'The document type is required.',
            'document_type.string'   => 'The document type must be a string.',
            'document_type.in'       => 'The document type must be one of: ' . implode(', ', SetupDocumentSignature::DOCUMENT_TYPES),
        ];
    }
}
