<?php

namespace App\Http\Requests\BoqItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoqItemRequest extends FormRequest
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
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'unit_price' => 'nullable|numeric|min:0',
            'draft_unit_price' => 'nullable|numeric|min:0',
        ];
    }
}
