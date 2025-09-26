<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBomRequest extends FormRequest
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
            'material_name' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|numeric|min:0',
            'unit' => 'sometimes|required|string|max:50',
            'unit_price' => 'sometimes|nullable|numeric|min:0',
            'additional_details' => 'sometimes|nullable|string',
        ];
    }
}
