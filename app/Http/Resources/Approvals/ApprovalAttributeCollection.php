<?php

namespace App\Http\Resources\Approvals;

use App\Services\HrmsServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalAttributeCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return HrmsServices::formatApprovals($request->bearerToken(), $this->resource);
    }
}
