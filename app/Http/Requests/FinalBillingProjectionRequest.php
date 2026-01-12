<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinalBillingProjectionRequest extends FormRequest
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
            'selected_month' => 'required|string',
            'selected_year' => 'required|integer|between:2000,2099',
        ];
    }
}
