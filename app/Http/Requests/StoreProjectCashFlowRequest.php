<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectCashFlowRequest extends FormRequest
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
            'date' => 'required|date',
            'percent' => 'required|numeric|min:0|max:1',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:resources,id',
            'items.*.amount' => 'required|numeric|min:0',
        ];
    }
}
