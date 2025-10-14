<?php

namespace App\Http\Requests;

use App\Enums\AccessibilityProjects;
use App\Exceptions\AuthorizationException;
use App\Http\Traits\CheckAccessibility;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDraftUnitPriceRequest extends FormRequest
{
    use CheckAccessibility;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkUserAccess([
            ...AccessibilityProjects::marketingGroup(),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'draft_unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
