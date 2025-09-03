<?php

namespace App\Http\Requests;

use App\Enums\ChangeRequestType;
use App\Enums\RequestStatuses;
use App\Traits\HasApprovalValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectChangeRequest extends FormRequest
{
    use HasApprovalValidation;
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
            'project_id' => ['required', 'exists:projects,id'],
            'request_type' => ['required', Rule::in(ChangeRequestType::values())],
            'changes' => ['nullable', 'json'],
            'request_status' => ['required', Rule::in(RequestStatuses::values())],
            'created_by' => ['required', 'exists:users,id'],
            ...$this->storeApprovals(),
        ];
    }
}
