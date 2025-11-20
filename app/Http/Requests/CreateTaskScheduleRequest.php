<?php
namespace App\Http\Requests;
use App\Enums\TimelineClassification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CreateTaskScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'item_id'                 => ['required', 'exists:tasks,id'],
            'start_date' => ['nullable', 'date'],
            'end_date'   => [
                'nullable',
                'date',
                'after:start_date',   // only applies when both exist
            ],
        ];
    }
}
