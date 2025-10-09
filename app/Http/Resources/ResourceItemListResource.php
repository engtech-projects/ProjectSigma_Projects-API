<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ResourceItemListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'setup_item_profile_id' => $this->setup_item_profile_id,
            'task_id' => $this->task_id,
            'resource_type' => $this->resource_type,
            'description' => $this->description,
            'unit_count' => $this->unit_count,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_name' => $this->unit_name,
            'unit_cost' => $this->unit_cost,
            'resource_cost' => $this->resource_cost,
            'total_cost' => $this->total_cost,
            'consumption_rate' => $this->consumption_rate,
            'labor_cost_category' => $this->labor_cost_category,
            'work_time_category' => $this->work_time_category,
            'remarks' => $this->remarks,
            'consumption_unit' => $this->consumption_unit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
