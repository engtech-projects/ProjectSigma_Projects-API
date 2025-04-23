<?php

namespace App\Http\Requests\ProjectAssignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectAssignmentRequest extends FormRequest
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
            'project_assignments' => ['required', 'min:1', 'array'],
            'project_assignments.*.id' => ['nullable', 'exists:project_assignment,id'],
            'project_assignments.*.employee_id' => ['required', 'exists:employees,id'],
            'project_assignments.*.start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'project_assignments.*.end_date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }
}
