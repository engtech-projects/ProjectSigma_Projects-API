<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreTaskScheduleWeekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'item_id'         => ['required', 'exists:tasks,id'],
            'week_start_date' => ['required', 'date'],
            'week_end_date'   => ['required', 'date', 'after_or_equal:week_start_date'],
        ];
    }
}
