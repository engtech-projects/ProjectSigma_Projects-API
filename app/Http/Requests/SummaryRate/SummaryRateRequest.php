<?php

namespace App\Http\Requests\SummaryRate;

use Illuminate\Foundation\Http\FormRequest;

class SummaryRateRequest extends FormRequest
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
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:resources,id',
            'unit_cost' => 'required|numeric',
        ];
    }
}
