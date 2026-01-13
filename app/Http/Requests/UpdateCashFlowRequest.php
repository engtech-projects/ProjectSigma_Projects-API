<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashFlowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if (gettype($this->cash_flow) == "string") {
            $this->merge([
                "cash_flow" => json_decode($this->cash_flow, true)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cash_flow' => 'required|array',
            'cash_flow.q1.accomplishment' => 'nullable|numeric',
            'cash_flow.q1.cash_flow' => 'nullable|numeric',
            'cash_flow.q1.cumulative_accomplishment' => 'nullable|numeric',
            'cash_flow.q1.cumulative_cash_flow' => 'nullable|numeric',
            'cash_flow.q2.accomplishment' => 'nullable|numeric',
            'cash_flow.q2.cash_flow' => 'nullable|numeric',
            'cash_flow.q2.cumulative_accomplishment' => 'nullable|numeric',
            'cash_flow.q2.cumulative_cash_flow' => 'nullable|numeric',
            'cash_flow.q3.accomplishment' => 'nullable|numeric',
            'cash_flow.q3.cash_flow' => 'nullable|numeric',
            'cash_flow.q3.cumulative_accomplishment' => 'nullable|numeric',
            'cash_flow.q3.cumulative_cash_flow' => 'nullable|numeric',
            'cash_flow.q4.accomplishment' => 'nullable|numeric',
            'cash_flow.q4.cash_flow' => 'nullable|numeric',
            'cash_flow.q4.cumulative_accomplishment' => 'nullable|numeric',
            'cash_flow.q4.cumulative_cash_flow' => 'nullable|numeric',
            'cash_flow.wtax.accomplishment' => 'nullable|numeric',
            'cash_flow.wtax.cash_flow' => 'nullable|numeric',
            'cash_flow.wtax.cumulative_accomplishment' => 'nullable|numeric',
            'cash_flow.wtax.cumulative_cash_flow' => 'nullable|numeric',
        ];
    }
}
