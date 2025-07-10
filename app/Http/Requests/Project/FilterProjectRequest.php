<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'stage' => ['nullable', new Enum(ProjectStage::class)],
        ];
    }
}
