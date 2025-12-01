<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskScheduleWeeklyRequest extends FormRequest
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
            'task_schedule_id' => 'required|exists:task_schedules,id',
            'week_start_date' => 'nullable|date:Y-m-d',
            'week_end_date' => 'nullable|date:Y-m-d|after:week_start_date',
        ];
    }
}
