<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employee\EmployeeCollection;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(config('services.pagination.limit'));
        return response()->json($employees, 200);
    }

    public function show(Employee $employee)
    {
        return response()->json(new EmployeeCollection($employee), 200);
    }
}
