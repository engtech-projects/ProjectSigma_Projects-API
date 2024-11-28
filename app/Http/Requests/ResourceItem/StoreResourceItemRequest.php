<?php

namespace App\Http\Requests\ResourceItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceItemRequest extends FormRequest
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
            'task_id' => ['required', 'exists:tasks,id'],
            'items' => ['required', 'min:1', 'array'],
            'items.*.id' => ['nullable', 'exists:resources,id'],
            'items.*.name_id' => ['required', 'exists:resource_names,id'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'items.*.unit' => ['required', 'string'],
            'items.*.unit_cost' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'items.*.resource_count' => ['required', 'integer'],
            'items.*.total_cost' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }
}
