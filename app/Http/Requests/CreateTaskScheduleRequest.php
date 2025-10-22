<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Enums\TimelineClassification;
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
            'timeline_classification' => ['required', Rule::in(TimelineClassification::values())],
            'item_id' => ['required', 'exists:tasks,id'],
            "name" => ['nullable', 'string', 'max:255'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'weight_percent' => ['nullable', 'numeric', 'between:0,100', 'decimal:0,2'],
            'status' => ['required', Rule::in(TaskStatus::values())]
        ];
    }
}
