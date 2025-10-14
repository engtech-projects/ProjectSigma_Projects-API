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
            'location' => 'required|string',
            'name' => 'required|string',
            'nature_of_work' => 'nullable|string',
            'implementing_office' => 'nullable|string|max:255',
            'license' => 'nullable|string',
            'designator' => 'nullable|string|max:255',
            'position' => 'nullable|string',
            'abc' => 'nullable|numeric|min:0',
            'bid_date' => 'nullable|date_format:Y-m-d',
            'duration' => 'nullable|integer|min:1',
            'amount' => 'nullable|numeric|decimal:0,2|min:0',
            'contract_date' => 'nullable|date|date_format:Y-m-d',
            'noa_date' => 'nullable|date|date_format:Y-m-d',
            'ntp_date' => 'nullable|date|date_format:Y-m-d',
            'document_number' => 'nullable|string',
        ];
    }
}
