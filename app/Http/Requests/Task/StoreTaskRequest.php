<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
			'phase_id' => ['required', 'exists:phases,id'],
			'tasks' => ['required', 'min:1', 'array'],
			'tasks.*.id' => ['nullable', 'exists:tasks,id'],
			'tasks.*.name' => ['required', 'string'],
			'tasks.*.description' => ['required', 'string'],
			'tasks.*.quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
			'tasks.*.unit' => ['required', 'string'],
			'tasks.*.unit_price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
			'tasks.*.amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }
}
