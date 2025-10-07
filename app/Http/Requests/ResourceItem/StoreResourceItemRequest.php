<?php

namespace App\Http\Requests\ResourceItem;

use App\Enums\LaborCostCategory;
use App\Enums\ResourceStatus;
use App\Enums\ResourceType;
use App\Enums\WorkTimeCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreResourceItemRequest extends FormRequest
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
            'task_id' => [
                'required',
                Rule::exists('tasks', 'id')->whereNull('deleted_at'),
            ],
            'setup_item_profile_id' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->status === ResourceStatus::ITEM->value && $this->resource_type === ResourceType::MATERIALS->value;
                })
            ],
            'resource_type' => ['required', new Enum(ResourceType::class)],
            'description'   => [
                'required',
                Rule::unique('resources')
                    ->where(fn ($q) => $q->where('task_id', $this->task_id)
                        ->where('unit', $this->unit)
                        ->where('resource_type', ResourceType::MATERIALS))
                    ->ignore($this->id),
            ],
            'unit_count' => 'nullable|integer|min:0',
            'quantity' => 'required|decimal:0,8|min:0',
            'unit' => 'required|string',
            'unit_cost' => 'required|decimal:0,8|min:0',
            'resource_count' => 'required|integer|min:0',
            'consumption_rate' => 'nullable|decimal:0,8|min:0',
            'consumption_unit' => 'nullable|string',
            'labor_cost_category' =>  ['nullable', new Enum(LaborCostCategory::class)],
            'work_time_category' => ['nullable', new Enum(WorkTimeCategory::class)],
            'remarks' => 'nullable|string',
            'status' => ['nullable', new Enum(ResourceStatus::class)]
        ];
    }
    public function messages()
    {
        return [
            'task_id.exists' => 'The task id does not exist',
            'setup_item_profile_id.required' => 'The setup item profile id is required when status is item and resource type is materials'
        ];
    }
}
