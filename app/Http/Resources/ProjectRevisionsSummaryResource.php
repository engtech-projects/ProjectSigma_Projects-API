<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectRevisionsSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_id' => $this->id,
            'project_code' => $this->code,
            'project_name' => $this->name,
            'version' => $this->version,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'revisions' => $this->revisions->map(function ($revision) {
                $decodedData = json_decode($revision->data, true);
                if (isset($decodedData['cash_flow']) && is_string($decodedData['cash_flow'])) {
                    $decodedData['cash_flow'] = json_decode($decodedData['cash_flow'], true);
                }
                return [
                    'id' => $revision->id,
                    'version' => $revision->version,
                    'data' => $decodedData,
                    'status' => $revision->status,
                    'created_at' => $revision->created_at,
                ];
            }),
        ];
    }

}
