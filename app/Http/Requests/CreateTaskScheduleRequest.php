<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskScheduleRequest extends FormRequest
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
            'item_id' => ['required','exists:tasks,id'],
            "name" => ['nullable', 'string', 'max:255'],
            'original_start' => ['required','date'],
            'original_end' => ['required', 'date','after:original_start'],
            'current_start' => ['nullable', 'date', 'before:current_end'],
            'current_end' => ['nullable', 'date', 'after:current_start'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'weight_percent' => ['nullable', 'numeric', 'between:0,100', 'decimal:0,2'],
            'status' => ['required', Rule::in(TaskStatus::values())]
        ];
    }
}
