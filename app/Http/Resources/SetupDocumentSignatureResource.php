<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetupDocumentSignatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'position'      => $this->position,
            'license'       => $this->license,
            'document_type' => $this->document_type,
            'signature_label' => $this->signature_label,
        ];
    }
}
