<?php

namespace App\Http\Resources\ResourceItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceItemCollection extends ResourceCollection
{
    public static $wrap = 'resources';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($resourceItem) {
            return new ResourceItemResource($resourceItem);
        })->toArray();
    }
}
