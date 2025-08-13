<?php

namespace App\Http\Requests\ResourceItem;

use App\Enums\LaborCostCategory;
use App\Enums\ResourceType;
use App\Enums\WorkTimeCategory;
use Illuminate\Foundation\Http\FormRequest;
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
            'task_id' => 'required|exists:tasks,id',
            'resource_type' => ['required', new Enum(ResourceType::class)],
            'description' => 'required|string',
            'unit_count' => 'nullable|integer',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'unit' => 'required|string',
            'unit_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'resource_count' => 'required|integer',
            'consumption_rate' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'consumption_unit' => 'nullable|string',
            'labor_cost_category' =>  ['nullable', new Enum(LaborCostCategory::class)],
            'work_time_category' => ['nullable', new Enum(WorkTimeCategory::class)],
            'remarks' => 'nullable|string',
        ];
    }
}
