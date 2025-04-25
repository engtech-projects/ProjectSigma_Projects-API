<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\EmployeeCollection;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShowEmployee extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Employee $employee)
    {
        return response()->json(EmployeeCollection::collection($employee), 200);
    }
}
