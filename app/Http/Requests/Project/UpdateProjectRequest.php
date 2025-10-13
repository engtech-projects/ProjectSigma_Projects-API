<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
            'contract_id' => 'required|string',
            'code' => [
                'nullable',
                'string',
                Rule::unique('projects', 'code')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('project')),
            ],
            'name' => 'required|string',
            'location' => 'required|string',
            'amount' => 'nullable|numeric|decimal:0,2|min:0',
            'contract_date' => 'nullable|date|date_format:Y-m-d',
            'duration' => 'nullable|integer|min:1',
            'noa_date' => 'nullable|date|date_format:Y-m-d',
            'ntp_date' => 'nullable|date|date_format:Y-m-d',
            'license' => 'nullable|string',
            'designator' => 'nullable|string|max:255',
            'position' => 'nullable|string',
            'nature_of_work' => 'nullable|string',
            'implementing_office' => 'nullable|string|max:255',
            'abc' => 'nullable|string',
            'bid_date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
