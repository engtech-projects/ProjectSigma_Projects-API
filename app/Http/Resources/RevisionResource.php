<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionResource extends JsonResource
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
            'project_id' => $this->project_id,
            'project_uuid' => $this->project_uuid,
            'data' => $this->getDeserializedData(),
            'comments' => $this->comments,
            'status' => $this->status,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function getDeserializedData(): array
    {
        $decoded = json_decode($this->data, true);
        if (isset($decoded['cash_flow']) && is_string($decoded['cash_flow'])) {
            $decoded['cash_flow'] = json_decode($decoded['cash_flow'], true);
        }
        return $decoded;
    }
}
