<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public static $wrap = 'project';
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    public function with(Request $request)
    {
        return [
            'message' => "d"
        ];
    }

}
