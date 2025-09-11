<?php

namespace App\Http\Requests;

use App\Enums\SignatorySource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrUpdateDocumentSignaturesRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow only authorized users if needed
        return true;
    }
    public function rules(): array
    {
        return [
            'document_type' => 'required|string|in:bill_of_quantities,detailed_estimates,cash_flow,summary_of_rates,bid_summary',
            'signatures'    => 'required|array',
            'signatures.*.id' => 'nullable|integer|exists:setup_document_signatures,id',
            'signatures.*.license' => 'required|string',
            'signatures.*.signature_label' => 'required|string',
            'signatures.*.signatory_source' => [
                'required',
                Rule::in(array_map(fn ($case) => $case->value, SignatorySource::cases()))
            ],
            'signatures.*.name' => 'required|string',
            'signatures.*.user_id' => [
                'nullable',
                'numeric',
                'required_if:signatures.*.signatory_source,internal',
            ],
            'signatures.*.position' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'document_type.in' => 'The document type must be a valid option.',
        ];
    }
}
