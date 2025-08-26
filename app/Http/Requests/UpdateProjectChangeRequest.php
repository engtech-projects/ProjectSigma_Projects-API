<?php

namespace App\Http\Requests;

use App\Enums\ChangeRequestType;
use App\Enums\RequestStatuses;
use App\Models\ProjectChangeRequest;
use App\Traits\HasApprovalValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectChangeRequest extends FormRequest
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
            "request_type" => "nullable",Rule::in(ChangeRequestType::values()),
            "changes" => "nullable|json",
            "request_status" => "nullable",Rule::in(RequestStatuses::values()),
        ];
    }
}
