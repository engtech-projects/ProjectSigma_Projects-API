<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\Employee\EmployeeResource;

class ShowEmployee extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Employee $employee)
    {
        return response()->json(new EmployeeResource($employee), 200);
    }
}
