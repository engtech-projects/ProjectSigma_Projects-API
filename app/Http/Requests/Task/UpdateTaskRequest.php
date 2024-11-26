<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
			'name' => ['required', 'string'],
			'description' => ['required', 'string'],
			'quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
			'unit' => ['required', 'string'],
			'unit_price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
			'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
	}
}
