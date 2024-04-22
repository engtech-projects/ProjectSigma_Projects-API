<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{


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
            'contract_id' => 'required|string|unique:projects,contract_id',
            'contract_name' => 'required|string',
            'contract_location' => 'required|string',
            'status' => [new Enum(ProjectStatus::class)],
            'project_code' => 'required|string|unique:projects,project_code',
            'project_identifier' => 'required|string',
            'contract_amount' => 'required',
            'contract_duration' => 'required|string',
            'implementing_office' => 'required|string',
            'nature_of_work' => 'required|string',
            'date_of_noa' => 'required|string|date',
            'date_of_contract' => 'required|date',
            'date_of_ntp' => 'required|date',
            'license' => 'required|string',
        ];
    }
}
