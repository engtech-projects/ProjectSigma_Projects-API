<?php

namespace App\Http\Requests\Phase;

use Illuminate\Foundation\Http\FormRequest;

class StorePhaseRequest extends FormRequest
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
			'project_id' => ['required', 'exists:projects,id'],
			'phases' => ['required', 'min:1', 'array'],
			'phases.*.id' => ['nullable', 'exists:phases,id'],
			'phases.*.name' => ['required', 'string'],
			'phases.*.description' => ['required', 'string'],
			'phases.*.total_cost' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }
}
