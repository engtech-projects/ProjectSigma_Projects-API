<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
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
