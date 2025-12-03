<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CumulativeBillingRequest extends FormRequest
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
            'year' => 'required|integer',
            'as_of_month' => 'required|integer',
            'as_of_year' => 'required|integer',
            'covered_month_from' => 'required|integer|min:1|max:12',
            'covered_month_to' => 'nullable|integer|after:covered_month_from|max:12|gte:covered_month_from',
        ];
    }
}
