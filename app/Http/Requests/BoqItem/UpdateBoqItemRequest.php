<?php

namespace App\Http\Requests\BoqItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoqItemRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'unit_price' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
        ];
    }
}
