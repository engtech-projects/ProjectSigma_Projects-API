<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNatureOfWorkRequest extends FormRequest
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
        $natureOfWorkId = $this->route('nature_of_work');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nature_of_work', 'name')->ignore($natureOfWorkId),
            ],
        ];
    }
}
