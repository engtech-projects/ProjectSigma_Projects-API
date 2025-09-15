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
            'code' => 'nullable|string|unique:projects,code',
            'location' => 'required|string',
            'name' => 'required|string',
            'nature_of_work' => 'nullable|string',
            'implementing_office' => 'nullable|string|max:255',
            'contract_id' => 'required|string',
            'license' => 'nullable|string',
            'designator' => 'nullable|string|max:255',
            'position' => 'required|string',
            'abc' => 'nullable|string',
            'bid_date' => 'nullable|date_format:Y-m-d',
            'duration' => 'nullable|integer',
            'amount' => 'nullable|decimal:0,2',
            'contract_date' => 'nullable|date|date_format:Y-m-d',
            'noa_date' => 'nullable|date|date_format:Y-m-d',
            'ntp_date' => 'nullable|date|date_format:Y-m-d',
        ];
    }
}
