<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectActitvityRequest extends FormRequest
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
            'item_id' => 'required|exists:tasks,id',
            'reference' => 'nullable|string',
            'quantity' => 'required|numeric',
            'schedule' => 'nullable|string',
            'work_description' => 'nullable|string',
            'duration' => 'required|numeric',
            'target' => 'required|numeric',
            'actual' => 'required|numeric',
        ];
    }
}
