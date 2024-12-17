<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Employee\EmployeeResource;

class EmployeeCollection extends ResourceCollection
{
    public static $wrap = 'employees';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($employee){
			return new EmployeeResource($employee);
		})->toArray();
    }
}