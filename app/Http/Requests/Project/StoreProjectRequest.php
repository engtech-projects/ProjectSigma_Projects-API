<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            'contract_id' => ['required', 'string'],
            'code' => ['nullable', 'string', 'unique:projects,code'],
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
            'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'contract_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'duration' => ['required', 'string'],
            'noa_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'ntp_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'license' => ['nullable', 'string'],
            'employee_id' => ['required', 'exists:employees,id'],
            'nature_of_work' => ['nullable', 'string'],
            'stage' => [Rule::enum(ProjectStage::class)],
            'status' => [Rule::enum(ProjectStatus::class)],
        ];
    }
}
