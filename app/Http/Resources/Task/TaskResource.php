<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'phase_id' => $this->phase_id,
			'name' => $this->name,
			'description' => $this->description,
			'quantity' => $this->quantity,
			'unit' => $this->unit,
			'unit_price' => $this->unit_price,
			'amount' => $this->amount,
		];
    }
}
