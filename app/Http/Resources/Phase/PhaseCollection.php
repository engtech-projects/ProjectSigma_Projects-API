<?php

namespace App\Http\Resources\Phase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PhaseCollection extends ResourceCollection
{
    public static $wrap = 'phases';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
		return $this->collection->transform(function ($phase){
			return new PhaseResource($phase);
		})->toArray();
    }
}
