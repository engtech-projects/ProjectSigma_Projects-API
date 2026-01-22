<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ProjectStage;
use App\Enums\TssStage;
class FilterProjectRequest extends FormRequest
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
            'project_key' => ['nullable', 'string'],
            'stage_status' => ['nullable', 'string', Rule::in(
                array_unique(
                    array_merge(
                        array_map(fn ($stage) => $stage->value, ProjectStage::cases()),
                        array_map(fn ($stage) => $stage->value, TssStage::cases())
                    )
                )
            )],
        ];
    }
    public function messages(): array
    {
        return [
            'project_key.required' => 'Project key is required.',
            'stage_status.in' => 'Invalid stage status.',
        ];
    }
}
