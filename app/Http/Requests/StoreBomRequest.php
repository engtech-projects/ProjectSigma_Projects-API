<?php

namespace App\Http\Requests;

use App\Enums\SourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBomRequest extends FormRequest
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
            'task_id' => 'nullable|exists:tasks,id',
            'resource_id' => 'nullable|exists:resources,id',
            'material_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'nullable|numeric|min:1',
            'amount' => 'nullable|numeric',
            'additional_details' => 'nullable|string',
            'source_type' => [Rule::in(SourceType::toArray())],
        ];
    }
}
