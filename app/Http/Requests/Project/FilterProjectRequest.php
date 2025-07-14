<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStage;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users or add logic here
    }

    public function rules(): array
    {
        return [
            'stage' => [
                'nullable',
                Rule::in(array_merge(
                    array_column(ProjectStage::cases(), 'value'),
                    [ProjectStatus::ONHOLD->value]
                )),
            ],
        ];
    }
}
