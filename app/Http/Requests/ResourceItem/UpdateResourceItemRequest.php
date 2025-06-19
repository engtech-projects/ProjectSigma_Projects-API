<?php

namespace App\Http\Requests\ResourceItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceItemRequest extends FormRequest
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
            'task_id' => 'required|exists:tasks,id',
            'name_id' => 'required|exists:resource_names,id',
            'id' => 'nullable|exists:resources,id',
            'description' => 'required|string',
            'unit_count' => 'nullable|integer',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'unit' => 'required|string',
            'unit_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'resource_count' => 'required|integer',
            'total_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}
