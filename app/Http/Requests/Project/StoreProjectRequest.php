<?php
namespace App\Http\Requests\Project;
use Illuminate\Foundation\Http\FormRequest;
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
            'contract_id' => 'required|string',
            'code' => 'nullable|string|unique:projects,code',
            'name' => 'required|string',
            'location' => 'required|string',
            'amount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'contract_date' => 'nullable|date|date_format:Y-m-d',
            'duration' => 'nullable|integer',
            'noa_date' => 'nullable|date|date_format:Y-m-d',
            'ntp_date' => 'nullable|date|date_format:Y-m-d',
            'license' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'nature_of_work' => 'nullable|string',
            'position' => 'required|string',
            'abc' => 'nullable|string',
            'bid_date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
