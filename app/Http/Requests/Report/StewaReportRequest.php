<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StewaReportRequest extends FormRequest
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
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'as_of_month' => 'required|integer|min:1',
            'as_of_day' => 'required|integer|min:1',
            'as_of_year' => 'required|integer|after_or_equal:year|min:2000|max:' . date('Y'),
        ];
    }
}
