<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\Employee\EmployeeCollection;

class GetAllEmployees extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $employees = Employee::latest()->paginate(10);
        return response()->json(new EmployeeCollection($employees), 200);
    }
}
