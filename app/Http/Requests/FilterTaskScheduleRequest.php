<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Enums\TimelineClassification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterTaskScheduleRequest extends FormRequest
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
            'timeline_classification' => ['nullable', Rule::in(TimelineClassification::values())],
            'title' => ['nullable', 'string'],
            'item_id' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in(TaskStatus::cases())],
            'sort_by' => ['nullable', 'string', 'in:name,created_at,updated_at'],
            'order' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
